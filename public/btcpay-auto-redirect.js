// BTCPay Auto Redirect Script
// This script detects payment success and automatically redirects

(function() {
    'use strict';
    
    console.log('BTCPay Auto Redirect Script Loaded');
    
    // Function to redirect to processing page
    function redirectToProcessing() {
        console.log('Redirecting to processing page...');
        window.location.href = window.location.origin + '/payment-processing';
    }
    
    // Function to redirect to failed page
    function redirectToFailed() {
        console.log('Redirecting to payment failed page...');
        window.location.href = window.location.origin + '/payment-failed';
    }
    
    // Check for payment status indicators
    function checkPaymentStatus() {
        // Check for FAILED indicators first
        const allElements = document.querySelectorAll('*');
        for (let element of allElements) {
            if (element.textContent) {
                const text = element.textContent.toLowerCase();
                
                // Failed indicators
                if (text.includes('expired') || 
                    text.includes('invalid') || 
                    text.includes('failed') ||
                    text.includes('timeout') ||
                    text.includes('cancelled')) {
                    console.log('Payment failed detected via text:', text);
                    setTimeout(redirectToFailed, 3000);
                    return 'failed';
                }
                
                // Success indicators
                if (text.includes('invoice paid') || 
                    text.includes('payment received') ||
                    text.includes('payment successful')) {
                    console.log('Payment success detected via text:', text);
                    setTimeout(redirectToProcessing, 3000);
                    return 'success';
                }
            }
        }
        
        // Check for CSS classes
        const failedIndicators = [
            '.alert-danger',
            '.payment-failed',
            '.invoice-expired',
            '.invoice-invalid',
            '[class*="failed"]',
            '[class*="expired"]',
            '[class*="invalid"]'
        ];
        
        const successIndicators = [
            '.alert-success',
            '.payment-success',
            '.invoice-paid',
            '[class*="success"]',
            '[class*="paid"]'
        ];
        
        // Check failed first
        for (let selector of failedIndicators) {
            if (document.querySelector(selector)) {
                console.log('Payment failed detected via CSS class');
                setTimeout(redirectToFailed, 3000);
                return 'failed';
            }
        }
        
        // Then check success
        for (let selector of successIndicators) {
            if (document.querySelector(selector)) {
                console.log('Payment success detected via CSS class');
                setTimeout(redirectToProcessing, 3000);
                return 'success';
            }
        }
        
        // Check URL parameters
        if (window.location.hash.includes('failed') || 
            window.location.search.includes('status=failed') ||
            window.location.search.includes('expired=true')) {
            console.log('Payment failed detected via URL');
            setTimeout(redirectToFailed, 2000);
            return 'failed';
        }
        
        if (window.location.hash.includes('paid') || 
            window.location.search.includes('status=paid') ||
            window.location.search.includes('success=true')) {
            console.log('Payment success detected via URL');
            setTimeout(redirectToProcessing, 2000);
            return 'success';
        }
        
        return 'pending';
    }
    
    // Monitor DOM changes for payment status
    function startMonitoring() {
        // Initial check
        const initialStatus = checkPaymentStatus();
        if (initialStatus !== 'pending') return;
        
        // Monitor DOM changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    const status = checkPaymentStatus();
                    if (status !== 'pending') {
                        observer.disconnect();
                    }
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Periodic check every 5 seconds
        const intervalCheck = setInterval(function() {
            const status = checkPaymentStatus();
            if (status !== 'pending') {
                clearInterval(intervalCheck);
                observer.disconnect();
            }
        }, 5000);
        
        // Stop monitoring after 10 minutes and redirect to failed
        setTimeout(function() {
            clearInterval(intervalCheck);
            observer.disconnect();
            console.log('BTCPay monitoring timeout - redirecting to failed');
            redirectToFailed();
        }, 600000);
    }
    
    // Start monitoring when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startMonitoring);
    } else {
        startMonitoring();
    }
    
})();
