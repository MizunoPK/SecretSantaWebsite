// ! Functions run on page-startup
updatePage();
clearPartyForm();
clearPeopleForm();

// ! General Functions used in multiple sections
function updatePage() {
    updatePartyTable();
    updatePeopleTable();
    updateInvitees();
    updatePartyDropdown("people-target-year");
    updatePartyDropdown("pair-year");
    updateTargetDropdown("", "people-target");
    updatePairTable();
}

function setupTableClick(tableID, populateFunction) {
    var table = document.getElementById(tableID);
    if ( table === null ) {
        return;
    }
    var cells = table.getElementsByTagName('td');

    for (var i = 0; i < cells.length; i++) {
        // Take each cell
        var cell = cells[i];
        // do something on onclick event for cell
        cell.onclick = function () {
            // Get the row id where the cell exists
            var rowId = this.parentNode.rowIndex;

            resetTableSelect(tableID);
            var rowSelected = table.getElementsByTagName('tr')[rowId];
            rowSelected.className += " selectedRow";

            // Populate the form
            populateFunction(rowSelected);
        }
    }
}
function resetTableSelect(tableID) {
    var table = document.getElementById(tableID);
    var rowsNotSelected = table.getElementsByTagName('tr');
    for (var row = 0; row < rowsNotSelected.length; row++) {
        rowsNotSelected[row].style.backgroundColor = "";
        rowsNotSelected[row].classList.remove('selectedRow');
    }
}

// Function: updatePartyDropdown
// Description: Gets the current list of parties and populates the dropdown that use that information
function updatePartyDropdown(selectID) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        if ( this.response !== "null" ) {
            // update the dropdown's html
            document.getElementById(selectID).innerHTML = this.responseText;
        }
    }
    };
    xmlhttp.open("GET","admin_controller.php?q=updatePartyDropdown",true);
    xmlhttp.send();
}

// Function: updateTargetDropdown
// Description: Gets the current list of potential targets for a given year, then updates the given dropdown
function updateTargetDropdown(year, selectID) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        if ( this.response !== "null" ) {
            // update the dropdown's html
            document.getElementById(selectID).innerHTML = this.responseText;
        }
    }
    };
    xmlhttp.open("GET","admin_controller.php?q=updateTargetDropdown&year=" + year,true);
    xmlhttp.send();
}

// ! Generating Santa Targets
$("#generateButton").click(checkGeneration);

// Function: checkGeneration
// Description: Checks whether or not we have generated before. In either case, bring up a confirmation message.
function checkGeneration() {
    // Check whether or not generation has happened before...
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        if ( this.response === "null" ) {
            alert("Error: Set up this year's party before generating targets.");
        }
        else if ( this.response === "1" ) {
            // We have generated before... so ask if they want to overwrite the last generation
            if ( confirm("Targets have already been generated this year. Do you want to overwrite the previously generated targets with new ones?") ) {
                generateSecretSantaTargets();
            }
        }
        else {
            // We have not generated before.. double check that they want to generate
            if ( confirm("Are you sure you want to begin generation?") ) {
                generateSecretSantaTargets();
            }
        }
    }
    };
    xmlhttp.open("GET","admin_ss_generation.php?q=check",true);
    xmlhttp.send();
}

// function: generateSecretSantaTargets
// Description: Attempts to make the pairings for the current year's secret santa targets
function generateSecretSantaTargets() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        alert(this.responseText);
        updatePage();
    }
    };
    xmlhttp.open("GET","admin_ss_generation.php?q=generate",true);
    xmlhttp.send();
}

// ! PARTY TABLE STUFF
// Function: updatePartyTable
// Description: Updates the party table to reflect the current state of the database
function updatePartyTable() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        if ( this.response === "null" ) {
            var emptyTableMsg = "<p class=\"emptyTable\">Database is empty.</p>"
            document.getElementById("partyTable").innerHTML = emptyTableMsg;
        }
        else {
            // update the table's html
            document.getElementById("partyTable").innerHTML = this.responseText;

            // set up the table being clickable
            setupTableClick('partyTable', populatePartyForm);
        }
    }
    };
    xmlhttp.open("GET","admin_controller.php?q=updateParty",true);
    xmlhttp.send();
}

// Function: updateInvitees
// Description: Updates the invitees list to reflect the current state of the database
function updateInvitees() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        if ( this.response === "null" ) {
            var emptyTableMsg = "<p class=\"emptyTable\">Database is empty.</p>"
            document.getElementById("party-invite-list").innerHTML = emptyTableMsg;
        }
        else {
            // update the html
            document.getElementById("party-invite-list").innerHTML = this.responseText;
        }
    }
    };
    xmlhttp.open("GET","admin_controller.php?q=updateInvitees",true);
    xmlhttp.send();
}

// When someone triggers the "new party" checkbox, invert the visibility of the new party options
$("#party-new").on("change", checkPartyNew);
function checkPartyNew() {
    if ( $("#party-new").prop("checked") ) {
        $("#party-reset-sec").css("display", "block");
        $("#party-invite-sec").css("display", "block");
    }
    else {
        $("#party-reset-sec").css("display", "none");
        $("#party-invite-sec").css("display", "none");
    }
}

// Party Form Submit Button:
$("#party-submit-button").click(function(e){
    e.preventDefault();

    // Get the required inputs
    var year = $("#party-year").val();
    var rsvp = $("#party-rsvp").val();
    var date = $("#party-date").val();
    var location = $("#party-location").val();
    var targetsAssigned = $("#party-targets").prop("checked");

    if ( year === "" || rsvp === "" || date === "" || location == "" ) {
        alert("Error: Must enter a party year, RSVP deadline, party date, and location");
        return;
    }

    // Set up the POST data
    var dataString = "q=partySubmit&year="+year + "&rsvp="+rsvp + "&date="+date + "&location="+location + "&targetsAssigned="+targetsAssigned;

    // If this is a new year: get the rest of the info
    if ( document.getElementById("party-new").checked ) {
        dataString+="&new=true";

        // Determine the reset flag
        dataString+="&reset=";
        if ( document.getElementById("party-reset").checked ) {
            dataString+="true";
        }
        else {
            dataString+="false";
        }

        // Determine who is being invited
        const invitees = [];
        $(".invitee").each(function(){
            if ($(this).prop("checked")) {
                invitees.push($(this).attr('data-id'));
            }
        });
        dataString+="&invitees=";
        dataString+=JSON.stringify(invitees);
    }
    // If it's an update: keep moving
    else {
        dataString+="&new=false";
    }

    // Do the AJAX call
    $.ajax({
        type: "POST",
        url: "admin_controller.php",
        data: dataString,
        cache: false,
        success: function(result){
            clearPartyForm();
            updatePage();
        }
    });
});

$("#party-reset-button").click(function(e){
    e.preventDefault();
    clearPartyForm();
});

// Function: clearPartyForm
// Description: Resets the party form
function clearPartyForm() {
    document.getElementById("party-year").value = "";
    document.getElementById("party-rsvp").value = "";
    document.getElementById("party-date").value = "";
    document.getElementById("party-location").value = "";
    $("#party-targets").prop("checked", false);
    $("#party-new").prop("checked", true);
    checkPartyNew();
    $("#party-reset").prop("checked", true);
    $(".invitee").each(function(){
        $(this).prop("checked", true);
    });

    resetTableSelect("partyTable");
}

// Function: populatePartyForm
// Description: For a given row from the table, populate the form accordingly
function populatePartyForm(rowSelected) {
    document.getElementById("party-year").value = rowSelected.cells[0].innerHTML;
    document.getElementById("party-rsvp").value = rowSelected.cells[1].innerHTML;
    document.getElementById("party-date").value = rowSelected.cells[2].innerHTML;
    document.getElementById("party-location").value = rowSelected.cells[3].innerHTML;
    document.getElementById("party-new").checked = false;
    checkPartyNew();
    document.getElementById("party-targets").checked = (rowSelected.cells[4].innerHTML === "1");
}

// ! People Table Stuff
// Function: updatePeopleTable
// Description: Updates the people table to reflect the current state of the database
function updatePeopleTable() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        if ( this.response === "null" ) {
            var emptyTableMsg = "<p class=\"emptyTable\">Database is empty.</p>"
            document.getElementById("peopleTable").innerHTML = emptyTableMsg;
        }
        else {
            // update the table's html
            document.getElementById("peopleTable").innerHTML = this.responseText;

            // set up the table being clickable
            setupTableClick("peopleTable", populatePeopleForm);
        }
    }
    };
    xmlhttp.open("GET","admin_controller.php?q=updatePeople",true);
    xmlhttp.send();
}

// Show Password click event
$("#people-show-pass").click(function(e) {
    togglePassword();
});
function togglePassword() {
    var passField = document.getElementById("people-password");
    if ( passField.type === "password" ) {
        passField.type = "text";
    }
    else {
        passField.type = "password";
    }
}
function hidePassword() {
    document.getElementById("people-password").type = "password";
    document.getElementById("people-show-pass").checked = false;
}

// When the party dropdown changes-> update the corresponding target dropdown
$("#people-target-year").on("change", function() {
    var year = $("#people-target-year option:selected").val();
    var targetSelID = "people-target";
    updateTargetDropdown(year, targetSelID);

    // If we are looking at a specific person: set the dropdown menu to the person's target if it exists
    var id = document.getElementById("people-id").value;
    if ( year !== "" && id !== "" ) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if ( this.response !== "null" ) {
                    // set the target dropdown menu
                    document.getElementById("people-target").value = this.responseText;
                }
            }
        };
        xmlhttp.open("GET","admin_controller.php?q=getTarget&id="+id+"&year="+year,true);
        xmlhttp.send();
    }
});

// Function: populatePeopleForm
// Description: called after clicking a row in the table... sets up being able to click 
function populatePeopleForm(rowSelected) {
    // Set the values stored in the table
    document.getElementById("people-fname").value = rowSelected.cells[0].innerHTML;
    document.getElementById("people-lname").value = rowSelected.cells[1].innerHTML;
    document.getElementById("people-invited").checked = (rowSelected.cells[2].innerHTML === "1");
    document.getElementById("people-rsvp").checked = (rowSelected.cells[3].innerHTML === "1");
    document.getElementById("people-attending").checked = (rowSelected.cells[4].innerHTML === "1");
    document.getElementById("people-ss").checked = (rowSelected.cells[5].innerHTML === "1");
    document.getElementById("people-admin").checked = (rowSelected.cells[6].innerHTML === "1");
    document.getElementById("people-ideas").value = rowSelected.cells[7].innerHTML;

    // Get and set the id
    var id = rowSelected.dataset.id;
    document.getElementById("people-id").value = id;

    // Get and set the password
    var password = rowSelected.dataset.pass;
    document.getElementById("people-password").value = password;
    hidePassword();

    // Ensure the target isn't set to anything
    document.getElementById("people-target-year").value = "";
    updateTargetDropdown("", "people-target");
}

$("#people-reset-button").click(function(e){
    e.preventDefault();
    clearPeopleForm();
});

// Function: clearPeopleForm
// Description: Resets the people form
function clearPeopleForm() {
    document.getElementById("people-id").value = "";
    document.getElementById("people-fname").value = "";
    document.getElementById("people-lname").value = "";
    document.getElementById("people-password").value = "";
    hidePassword();
    document.getElementById("people-ideas").value = "";
    $("#people-admin").prop("checked", false);
    $("#people-invited").prop("checked", false);
    $("#people-rsvp").prop("checked", false);
    $("#people-attending").prop("checked", false);
    $("#people-ss").prop("checked", false);
    document.getElementById("people-target-year").value = "";
    document.getElementById("people-target").value = "";
    updateTargetDropdown("", "people-target");
    

    resetTableSelect("peopleTable");
}

// PEOPLE FORM SUBMIT BUTTON
$("#people-submit-button").click(function(e) {
    e.preventDefault();

    // Get the data
    var id = $("#people-id").val();
    var fname = $("#people-fname").val();
    var lname = $("#people-lname").val();
    if ( fname === "" || lname === "" ) {
        alert("Error: Enter a name");
        return;
    }
    var admin = $("#people-admin").prop("checked");
    var invited = $("#people-invited").prop("checked");
    var rsvp = $("#people-rsvp").prop("checked");
    var attending = $("#people-attending").prop("checked");
    var ss = $("#people-ss").prop("checked");
    var password = $("#people-password").val();
    var ideas = $("#people-ideas").val();
    var targetYear = $("#people-target-year").val();
    var target = $("#people-target").val();

    // Store the data
    var dataString = 
        "q=peopleSubmit"
        + "&id=" + id
        + "&fname=" + fname
        + "&lname=" + lname
        + "&admin=" + admin
        + "&invited=" + invited
        + "&rsvp=" + rsvp
        + "&attending=" + attending
        + "&ss=" + ss
        + "&password=" + password
        + "&ideas=" + ideas
        + "&targetYear=" + targetYear
        + "&target=" + target;

    // Send the AJAX request
    $.ajax({
        type: "POST",
        url: "admin_controller.php",
        data: dataString,
        cache: false,
        success: function(result){
            clearPeopleForm();
            updatePage();
        }
    });
});

// ! PAIRS
// Function: updatePairTable
// Description: updates the pair table to reflect the database and selected year
function updatePairTable() {
    // If no year is selected: don't draw a table\
    var year = $("#pair-year").val();
    if ( year === "" || year === null ) {
        document.getElementById("pairTable").innerHTML = "<p class=\"note\">Select a Party Year to view its Secret Santa pairings.</p>";
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if ( this.response === "null" ) {
                var emptyTableMsg = "<p class=\"emptyTable\">Database is empty.</p>"
                document.getElementById("pairTable").innerHTML = emptyTableMsg;
            }
            else {
                // update the table's html
                document.getElementById("pairTable").innerHTML = this.responseText;
            }
        }
        };
        xmlhttp.open("GET","admin_controller.php?q=updatePairs&year="+year,true);
        xmlhttp.send();
    }
}

// Whenever the year changes: update the pair table
$("#pair-year").on("change", updatePairTable);

// Delete Year's Pairings Button:
$("#deleteYearBtn").click(function(){
    // If no year is selected: just ignore the press
    var year = $("#pair-year").val();
    if ( year === "" || year === null ) {
        return;
    }

    // Confirm if they actually want to delete
    if ( confirm("Are you sure you want to delete all pairings from the " + year + " party?") ) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            updatePage();
        }
        };
        xmlhttp.open("GET","admin_controller.php?q=deleteYearPairs&year="+year,true);
        xmlhttp.send();
    }
});