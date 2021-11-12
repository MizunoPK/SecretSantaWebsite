// ! Functions run on page-startup
updateParty();
updatePeople();

// ! General Functions used in multiple sections
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

            var rowsNotSelected = table.getElementsByTagName('tr');
            for (var row = 0; row < rowsNotSelected.length; row++) {
                rowsNotSelected[row].style.backgroundColor = "";
                rowsNotSelected[row].classList.remove('selectedRow');
            }
            var rowSelected = table.getElementsByTagName('tr')[rowId];
            rowSelected.className += " selectedRow";

            // Populate the form
            populateFunction(rowSelected);
        }
    }
}

// ! Generating Santa Targets
// function: generateSecretSantaTargets
// Description: Attempts to make the pairings for the current year's secret santa targets
function generateSecretSantaTargets() {
    
}

// ! PARTY TABLE STUFF
// Function: updateParty
// Description: Updates the party table to reflect the current state of the database
function updateParty() {
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

// Function: updatePeople
// Description: Updates the people table to reflect the current state of the database
function updatePeople() {
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
            // setupSelectProduct();
        }
    }
    };
    xmlhttp.open("GET","admin_controller.php?q=updatePeople",true);
    xmlhttp.send();

    updateInvitees();
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

    if ( year === "" || rsvp === "" || date === "" || location == "" ) {
        alert("Error: Must enter a party year, RSVP deadline, party date, and location");
        return;
    }

    // Set up the POST data
    var dataString = "q=partySubmit&year="+year + "&rsvp="+rsvp + "&date="+date + "&location="+location;

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
            updateParty();
            updatePeople();
        }
    });
});

$("#party-reset-button").click(function(e){
    e.preventDefault();
    clearPartyForm();
});

function clearPartyForm() {
    document.getElementById("party-year").value = "";
    document.getElementById("party-rsvp").value = "";
    document.getElementById("party-date").value = "";
    document.getElementById("party-location").value = "";
    $("#party-new").prop("checked", true);
    checkPartyNew();
    $("#party-reset").prop("checked", true);
    $(".invitee").each(function(){
        $(this).prop("checked", true);
    });
}

function populatePartyForm(rowSelected) {
    document.getElementById("party-year").value = rowSelected.cells[0].innerHTML;
    document.getElementById("party-rsvp").value = rowSelected.cells[1].innerHTML;
    document.getElementById("party-date").value = rowSelected.cells[2].innerHTML;
    document.getElementById("party-location").value = rowSelected.cells[3].innerHTML;
    document.getElementById("party-new").checked = false;
    checkPartyNew();
}