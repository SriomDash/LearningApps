<!DOCTYPE html>
<html>
<head>
    <title>Customer Manager</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .form-container, .data-table {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 0;
        }

        input, button, select {
            padding: 10px;
            margin: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: calc(30% - 12px);
        }

        input:focus, select:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #f5f7fb;
        }

        .action-btn {
            cursor: pointer;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            margin-right: 4px;
        }

        .delete {
            background: #dc3545;
            color: white;
        }

        .edit {
            background: #28a745;
            color: white;
        }

        .checkbox-group {
            margin-top: 10px;
        }

        .checkbox-group label {
            margin-right: 15px;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="form-container">
        <h2>Add Customer</h2>
        <input type="text" id="name" placeholder="Name">
        <input type="text" id="email" placeholder="Email">
        <input type="text" id="phone" placeholder="Phone Number">
        <button id="addBtn">Add</button>
        <div class="error" id="error"></div>
    </div>

    <div class="data-table" id="dataArea">
        <!-- Table will load here -->
    </div>

</div>

<script>
function loadData() {
    $.get("addData.php", function(data) {
        $("#dataArea").html(data);
    });
}

function validateField(field, value) {
    const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    const phoneRegex = /^\d{10}$/;

    if (field === "email" && !emailRegex.test(value)) return "Invalid email format!";
    if (field === "phone" && !phoneRegex.test(value)) return "Phone must be 10 digits!";
    if (field === "name" && value.trim() === "") return "Name cannot be empty!";
    return "";
}

$(document).ready(function () {
    loadData();

    $("#addBtn").click(function () {
        const name = $("#name").val().trim();
        const email = $("#email").val().trim();
        const phone = $("#phone").val().trim();

        const error =
            validateField("name", name) ||
            validateField("email", email) ||
            validateField("phone", phone);

        if (error) return $("#error").text(error);

        $.post("addData.php", { name, email, phone }, function (response) {
            if (response.includes("Error")) {
                $("#error").text(response);
            } else {
                $("#error").text("");
                $("#name, #email, #phone").val("");
                loadData();
            }
        });
    });

    $(document).on("click", ".delete", function () {
        const id = $(this).data("id");
        $.post("removeData.php", { id }, loadData);
    });

    $(document).on("click", ".edit", function () {
        const id = $(this).data("id");

        let fieldsToUpdate = [];

        const html = `
            <div>
                <h3>Select fields to update</h3>
                <div class="checkbox-group">
                    <label><input type="checkbox" id="chkName"> Name</label>
                    <label><input type="checkbox" id="chkEmail"> Email</label>
                    <label><input type="checkbox" id="chkPhone"> Phone</label>
                </div>
            </div>
        `;

        $("body").append(`<div id="fieldPopup" class="form-container">${html}<button id="proceedUpdate">Proceed</button></div>`);

        $("#proceedUpdate").click(function () {
            let data = { id: id };

            if ($("#chkName").is(":checked")) {
                const name = prompt("Enter new name:");
                const err = validateField("name", name);
                if (err) return alert(err);
                data.name = name;
            }

            if ($("#chkEmail").is(":checked")) {
                const email = prompt("Enter new email:");
                const err = validateField("email", email);
                if (err) return alert(err);
                data.email = email;
            }

            if ($("#chkPhone").is(":checked")) {
                const phone = prompt("Enter new phone:");
                const err = validateField("phone", phone);
                if (err) return alert(err);
                data.phone = phone;
            }

            $("#fieldPopup").remove();

            $.post("updateData.php", data, function (response) {
                if (response.includes("Error")) {
                    alert(response);
                } else {
                    loadData();
                }
            });
        });
    });
});
</script>
</body>
</html>
