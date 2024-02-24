<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Chatbot in PHP | AppsTy</title>
    <link rel="stylesheet" href="STYLE.CSS">
    <!-- ... other head elements ... -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        /* Style for email input field */
#emailid {
    height: 40px;
    width: calc(100% - 85px); /* Adjusted width to accommodate the button */
    outline: none;
    border: 1px solid transparent;
    padding: 0 15px; /* Adjusted padding */
    border-radius: 3px;
    font-size: 15px;
    background: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Style for send button */
#send-btn {
    height: 40px;
    width: 70px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    outline: none;
    border: none;
    border-radius: 3px;
    background: #3498db;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Hover effect for send button */
#send-btn:hover {
    background: #2980b9;
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Animation for send button */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
</head>
<body>
    <div class="wrapper">
        <div class="title" id="chatbot-toggle">
            <img src="WhatsApp Image 2024-01-19 at 12.55.40_041848df.jpg" alt="Chatbot Icon" width="80" height="80">
        </div>
        <form id="myForm">
            <div class="form" style="display: none;"></div>
            <div class="typing-field">
                <div class="input-data">
                    <input id="data" type="text" placeholder="Type something here.." name="data" required>
                </div>
            </div>
        </form>
        <div id="email-container" style="display: none;">
            <input id="emailid" type="email" placeholder="Your Email" name="emailid" autocomplete="email">
            <button id="send-btn">Send</button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let expectingName = true;
            let expectingDetails = false;
            let expectingMobile = false;
            let expectingEmail = true; // Set to true to initially show email input field and send button

            // Function to add message to chat
            function addMessage(className, message) {
                const msg = `<div class="${className} inbox"><div class="msg-header"><p>${message}</p></div></div>`;
                $(".form").append(msg);
            }

            // Function to add option button to chat
            function addOptionButton(option, text) {
                const btn = $('<button>', {
                    'class': 'option-btn',
                    'data-option': option,
                    text: text
                });
                $(".form").append(btn);
            }

            // Function to display service information
            function getServiceInfo(service) {
                const serviceData = {
                    'ITInfrastructure': `
                        We Offer IT Infrastructure Solutions!
                        AppsTech Solution provides an exceptional blend of hardware, software, storage, and security to operate & manage IT Infrastructure solutions environments for everyday business activities.
                    `,
                    'Networking': `
                        Networking Solutions
                        AppsTech Solution offers IT Networking services, including the supply of network components for the interconnectivity of users and external systems.
                    `,
                    'ITDesignConsultencySolutions': `
                        IT Designing Consultancy
                        We can assist the client in developing IT infrastructure solutions and provide a specialized, unbiased approach to evaluation.
                    `,
                    'AMCSolutions': `
                        AMC Services
                        All IT Infrastructure Solutions with AMC support with dedicated resources.
                    `,
                    'HardwareSolutions': `
                        Hardware Solutions provides comprehensive hardware services including server solutions, storage solutions, and desktop/laptop support to ensure efficient and reliable IT infrastructure for organizations.
                    `,
                    'CyberSecurity': `
                        Cyber Security Solutions offer essential audits, including network security, firewall, and server assessments, to fortify digital defenses and safeguard against potential threats.
                    `,
                    'ManagedITServices': `
                        Managed IT Services encompass comprehensive support, including cloud services, data backup and recovery solutions, and remote system management assistance, ensuring seamless IT operations and data protection for businesses.
                    `,
                    'FMSSolutions': `
                        FMS Solutions offer specialized support with IT engineers proficient in various domains including IT support, desktop management, network infrastructure, and server maintenance, ensuring efficient and reliable IT operations tailored to organizational needs.
                    `,
                };

                if (serviceData.hasOwnProperty(service)) {
                    const serviceInfo = serviceData[service];
                    addMessage("bot-inbox", serviceInfo);
                } else {
                    const btn = $('<button>', {
                        'class': 'option-btn',
                        'data-option': service,
                        text: service
                    });
                    $(".form").append(btn);
                }
            }

            // Function to validate name containing only alphabetic characters
            function isValidName(name) {
                // Regular expression to match alphabetic characters
                const nameRegex = /^[A-Za-z]+$/;
                return nameRegex.test(name);
            }

            // Function to validate query containing only alphabetic characters
            function isValidQuery(query) {
                // Regular expression to match alphabetic characters
                const queryRegex = /^[A-Za-z]+$/;
                return queryRegex.test(query);
            }

            // Add initial message when document is ready
            addMessage("bot-inbox", "Hey, I am AppsTy, what's your name?");

            // Click event handler for send button
            $(document).on("click", "#send-btn", function (event) {
                event.preventDefault();
                const value = $("#data").val().trim();
                const emailValue = $("#emailid").val().trim();

                // Basic input validation for name
                if (value === '') {
                    alert('Please enter your name.');
                    return;
                }

                // Validate name containing only alphabetic characters
                if (!isValidName(value)) {
                    alert('Please enter a valid name containing only alphabetic characters.');
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "MESSAGE.PHP",  // Replace with the correct path to your PHP file
                    data: { text: value, emailid: emailValue },
                    success: function (response) {
                        console.log(response);
                    }
                });

                $(".form").empty();

                if (expectingName) {
                    addMessage("user-inbox", value);
                    addMessage("bot-inbox", `Hi ${value}, how can I help you?`);
                    addMessage("bot-inbox", "Choose an option:");
                    addOptionButton("services", "Services");
                    addOptionButton("others", "Others");
                    expectingName = false;
                } else if (expectingDetails) {
                    if (expectingMobile) {
                        addMessage("user-inbox", value);
                        addMessage("bot-inbox", "Thank you! Now, please provide your email id:");
                        expectingMobile = false;
                        expectingEmail = true;
                        $("#email-container").show(); // Show email input field and send button container
                    } else if (expectingEmail) {
                        addMessage("user-inbox", value);
                        addMessage("bot-inbox", "Thank you for contacting AppsTy! We will get in touch with you shortly.");
                        expectingDetails = false;
                        expectingEmail = false;
                    }
                } else {
                    addMessage("user-inbox", value);
                    addMessage("bot-inbox", "Thank you for contacting AppsTy!");
                }

                $("#data").val('');
            });

            // Click event handler for option buttons
            $(".form").on("click", ".option-btn", function () {
                const selectedOption = $(this).data("option");

                if (selectedOption === "services") {
                    addMessage("bot-inbox", "Choose a service:");
                    addOptionButton("Networking", "Networking");
                    addOptionButton("CyberSecurity", "CyberSecurity");
                    addOptionButton("ITInfrastructure", "ITInfrastructure");
                    addOptionButton("ManagedITServices", "ManagedITServices");
                    addOptionButton("FMSSolutions", "FMSSolutions");
                    addOptionButton("HardwareSolutions", "HardwareSolutions");
                    addOptionButton("ITDesignConsultencySolutions", "ITDesignConsultencySolutions");
                    addOptionButton("AMCSolutions", "AMCSolutions");
                } else if (selectedOption === "others") {
                    addMessage("bot-inbox", "Please specify your query:");
                    addInputField();
                    expectingDetails = true;
                } else {
                    getServiceInfo(selectedOption);
                }
            });

            // Function to add input field
            function addInputField() {
                const inputField = '<div class="input-data"><input id="queryData" type="text" placeholder="Type your query here.." required><button id="submit-query-btn">Submit</button></div>';
                $(".form").append(inputField);

                // Set the width of the email input field to match the query input field
                const queryInputWidth = $("#data").outerWidth();
                $("#emailid").css("width", queryInputWidth);
            }

            // Click event handler for submit query button
            $(document).on("click", "#submit-query-btn", function () {
                const queryValue = $("#queryData").val().trim();
                const emailValue = $("#emailid").val().trim();

                // Basic input validation for query
                if (queryValue === '') {
                    alert('Please enter your query.');
                    return;
                }

                // Validate query containing only characters
                if (!isValidQuery(queryValue)) {
                    alert('Please enter a valid query containing only characters.');
                    return;
                }

                if (expectingDetails) {
                    if (expectingMobile) {
                        addMessage("user-inbox", queryValue);
                        addMessage("bot-inbox", "Thank you! Now, please provide your email id:");
                        expectingMobile = false;
                        expectingEmail = true;
                    }else if (expectingEmail) {
            addMessage("user-inbox", emailValue);
            addMessage("bot-inbox", "Thank you for the details! We will contact you shortly."); // Add the "thank you" message here
            expectingDetails = false;
            expectingEmail = false;
        }
                } else {
                    addMessage("user-inbox", queryValue);
                    addMessage("bot-inbox", "Bot's response goes here.");
                }

                $.ajax({
                    type: "POST",
                    url: "MESSAGE.PHP",  // Replace with the correct path to your PHP file
                    data: { userQuery: queryValue, emailid: emailValue },
                    success: function (response) {
                        console.log(response);  // Log the response from the server

                        // Update the chat based on the server's response
                        $(".form").empty().append(response);

                        $("#queryData").val('');
                        $("#emailid").val('');
                    }
                });
            });

            // Toggle email input field based on the state of expectingEmail
            $("#data").on("input", function () {
                if (expectingEmail) {
                    const inputValue = $(this).val().trim();
                    if (inputValue.length > 0) {
                        $("#email-container").show(); // Show email input field and send button container
                    } else {
                        $("#email-container").hide(); // Hide email input field and send button container
                    }
                }
            });

            // Toggle chatbot visibility on image click
            $("#chatbot-toggle").on("click", function () {
                $(".form").toggle();

                // Hide email input field and send button container when toggling chatbot
                $("#email-container").hide();
            });
        });
    </script>
</body>
</html>




 

