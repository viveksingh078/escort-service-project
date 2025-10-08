document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("input.file-uploder").forEach(function (input) {
        // Create wrapper
        const wrapper = document.createElement("div");
        wrapper.classList.add("file-uploder-wrapper");

        // Create custom button
        const label = document.createElement("label");
        label.classList.add("file-uploder-btn");
        label.innerHTML = '<i class="fa fa-upload"></i> Select File';
        label.setAttribute("for", input.id);

        // Create filename display (below)
        const fileName = document.createElement("span");
        fileName.classList.add("file-uploder-filename");
        fileName.textContent = "No file chosen";

        // Insert elements
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input); // input stays hidden
        wrapper.appendChild(label);
        wrapper.appendChild(fileName);

        // Update filename on change
        input.addEventListener("change", function () {
            fileName.textContent = input.files.length > 0 ? input.files[0].name : "No file chosen";
        });
    });
});
