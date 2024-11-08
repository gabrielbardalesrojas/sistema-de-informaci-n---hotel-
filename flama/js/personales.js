function openForm() {
    document.getElementById("employeeForm").style.display = "block";
}

function closeForm() {
    document.getElementById("employeeForm").style.display = "none";
}

function showSelection(selectId) {
    const selectElement = document.getElementById(selectId);
    const selectedValue = selectElement.options[selectElement.selectedIndex].text;
    document.getElementById('selected_' + selectId).innerText = "Seleccionado: " + selectedValue;
}