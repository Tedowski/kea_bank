$(window).on('load', function() {
    $.ajax({
        method: "GET",
        url: "apis/api-get-target-data",
        data: {
            "objectToUpdate": "transactions"
        },
        dataType: "JSON"
    })
    .done(function (jData) {
        //console.log(jData);
        if(jData.status == -1){
            swal({
                title: "Error",
                text: jData.message,
                icon: "error",
              });
        } else if(jData.status == 0) {
            swal({
                title: "Warning",
                text: jData.message,
                icon: "warning",
              });
        } else if(jData.status == 1) {
            displayTransactions(jData.data);
        }
    })
    .fail(function() {
        console.log('Ajax error');
    });
});

// Event Handlers *************************************************************************

$('.pointer').on('click', function() {
    let dataToUpdate = $(this).attr("data-toUpdate");
    let pageToShow = $(this).attr("data-toOpen");
    console.log(pageToShow);

    $('.page').addClass('hidden');
    $($(this).attr("data-toOpen")).removeClass('hidden');
    $(window).scrollTop(0);

    if( dataToUpdate != undefined) {
        fnUpdateData(dataToUpdate);
    }
});

$('#cardsDiv').on('click', '.cancelCard', function (){
    let cardToCancel = $(this).attr("data-cardnumber");
    // console.log(cardToCancel);

    if( cardToCancel != null || cardToCancel != null) {
        fnCancelCard(cardToCancel);
    }
});

$('#requestsDiv').on('click', '.approveRequest', function (){
    let requestToApprove = $(this).attr("data-requestNumber");

    if( requestToApprove != null || requestToApprove != null) {
        fnApproveRequest(requestToApprove);
    }
});

// AJAX Calls *************************************************************************

function fnUpdateData(objectToUpdate) {
    $.ajax({
        method: "GET",
        url: "apis/api-get-target-data",
        data: {
            "objectToUpdate": objectToUpdate
        },
        dataType: "JSON"
    })
    .done(function (jData) {
        //console.log(jData);
        if(jData.status == -1){
            swal({
                title: "Error",
                text: jData.message,
                icon: "error",
              });
        } else if(jData.status == 0) {
            swal({
                title: "Warning",
                text: jData.message,
                icon: "warning",
              });
        } else if(jData.status == 1) {
            if( objectToUpdate == "accounts") {
                displayAccounts(jData.data);
            } else if( objectToUpdate == "loans") {
                displayLoans(jData.data);
            } else if( objectToUpdate == "cards") {
                displayCards(jData.data);
            } else if( objectToUpdate == "requests") {
                displayRequests(jData.data);
            } else if( objectToUpdate == "transactions") {
                displayTransactions(jData.data);
            }
        }
    })
    .fail(function() {
        console.log('Ajax error');
    });
}

function fnCancelCard(cardToCancel) {
    $.ajax({
        method: "GET",
        url: "apis/api-cancel-creditcard",
        data: {
            "cardToCancel": cardToCancel
        },
        dataType: "JSON"
    })
    .done(function (jData) {
        //console.log(jData);
        if(jData.status == -1){
            swal({
                title: "Error",
                text: jData.message,
                icon: "error",
              });
        } else if(jData.status == 0) {
            swal({
                title: "Warning",
                text: jData.message,
                icon: "warning",
              });
        } else if(jData.status == 1) {
            console.log(jData.data);
            swal({
                title: "Success!",
                text: jData.message,
                icon: "success",
              });
            displayCards(jData.data);
        }
    })
    .fail(function() {
        console.log('Ajax error');
    });
}

function fnApproveRequest(requestToApprove) {
    $.ajax({
        method: "GET",
        url: "apis/api-approve-request",
        data: {
            "requestToApprove": requestToApprove
        },
        dataType: "JSON"
    })
    .done(function (jData) {
        //console.log(jData);
        if(jData.status == -1){
            swal({
                title: "Error",
                text: jData.message,
                icon: "error",
              });
        } else if(jData.status == 0) {
            swal({
                title: "Warning",
                text: jData.message,
                icon: "warning",
              });
        } else if(jData.status == 1) {
            console.log(jData.data);
            swal({
                title: "Success!",
                text: jData.message,
                icon: "success",
              });
            displayRequests(jData.data);
        }
    })
    .fail(function() {
        console.log('Ajax error');
    });
}

// Display functions *************************************************************************

function displayAccounts(jDataToDisplay) {
    // console.log(jDataToDisplay);

    // Select template and parent element
    let template = $('#accountTemplate').contents();
    let parent = $('#accountsDiv');

    // If there are data already displayed, remove them first (prevents stacking)
    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    // Loop through the JSON
    for (var sAccountKey in jDataToDisplay) {
        let value = jDataToDisplay[sAccountKey];
        // console.log(value);

        // Create clone for each instance
        let clone = $(template).clone(true);
        let accountType = $(clone).find('#accountType');
        let accountBalance = $(clone).find('#accountBalance');
        let accountCurrency = $(clone).find('#accountCurrency');
        let accountID = $(clone).find('#accountID');
        let accountCard = $(clone).find('#accountCard');

        // Assign values
        $(accountType).text(value.type);
        $(accountBalance).text(value.balance);
        $(accountCurrency).text(value.currency);
        $(accountID).text(sAccountKey);
        $(accountCard).text(value.card);

        // Append to the parent
        $(parent).append(clone);
    }
}

function displayCards(jDataToDisplay) {
    // console.log(jDataToDisplay);
    let template = $('#cardTemplate').contents();
    let parent = $('#cardsDiv');
    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    for (var sCardNo in jDataToDisplay) {
        let value = jDataToDisplay[sCardNo];
        // console.log(value);

        let clone = $(template).clone(true);
        let cardNumber = $(clone).find('#cardNumber');
        let cardType = $(clone).find('#cardType');
        let cardCVV = $(clone).find('#cardCVV');
        let cardAccount = $(clone).find('#cardAccount');
        let cancelButton = $(clone).find('.cancelCard');

        $(cardNumber).text(sCardNo);
        $(cardType).text(value.type);
        $(cardCVV).text(value.cvv);
        $(cardAccount).text(value.account);

        $(cancelButton).attr('data-cardNumber', sCardNo);

        // To do: Generate a link that will cancel the card
        // The script will generate a button with class ('.cancelCard') and data attribute with that creditcard's number
        // We will bind an AJAX call to that ID on click
        // The ajax will point to api-cancel-card
        // The ajax call will use method GET
        // The Api will find the card -> remove it? make a cancelation request for Admin?

        $(parent).append(clone);
    }
}

function displayLoans(jDataToDisplay) {
    // console.log(jDataToDisplay);
    let template = $('#loanTemplate').contents();
    let parent = $('#loansDiv');
    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    for (var sLoanID in jDataToDisplay) {
        let value = jDataToDisplay[sLoanID];
        // console.log(value);

        let clone = $(template).clone(true);
        let loanAmount = $(clone).find('#loanAmount');
        let loanDate = $(clone).find('#loanDate');
        let loanAccount = $(clone).find('#loanAccount');
        let loanDueDate = $(clone).find('#loanDueDate');

        $(loanAmount).text(value.amount);
        $(loanDate).text(returnDateFormat(value.date, '/'));
        $(loanAccount).text(value.account);
        $(loanDueDate).text(returnDateFormat(value.dueDate, '/'));

        $(parent).append(clone);
    }
}

function displayRequests(jDataToDisplay) {
    // console.log(jDataToDisplay);
    let template = $('#requestTemplate').contents();
    let parent = $('#requestsDiv');
    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    for (var sRequestID in jDataToDisplay) {
        let value = jDataToDisplay[sRequestID];
        // console.log(value);

        let clone = $(template).clone(true);
        let requestFrom = $(clone).find('#requestFrom');
        let requestAmount = $(clone).find('#requestAmount');
        let requestDate = $(clone).find('#requestDate');
        let requestID = $(clone).find('#requestID');
        let approveButton = $(clone).find('.approveRequest');

        $(requestFrom).text(value.from);
        $(requestAmount).text(value.amount);
        $(requestDate).text(returnDateFormat(value.date, '/'));
        $(requestID).text(sRequestID);
        $(approveButton).attr('data-requestNumber', sRequestID);

        $(parent).append(clone);
    }
}

function displayTransactions(jDataToDisplay) {
    // console.log(jDataToDisplay);
    let template = $('#transactionTemplate').contents();
    let parent = $('#transactionsDiv');
    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    for (var sTransactionID in jDataToDisplay) {
        let value = jDataToDisplay[sTransactionID];
        // console.log(value);

        let clone = $(template).clone(true);
        let transactionAmount = $(clone).find('#transactionAmount');
        let transactionFullName = $(clone).find('#transactionFullName');
        let transactionFromPhone = $(clone).find('#transactionFromPhone');
        let transactionDate = $(clone).find('#transactionDate');
        let transactionMessage = $(clone).find('#transactionMessage');
        let transactionId = $(clone).find('#transactionId');

        let fullName = value.name + ' ' + value.lastName;
        let direction = value.direction;

        if(direction == "in") {
            $(transactionAmount).css('color', '#01B25A');
            $(transactionAmount).text(value.amount);
        } else if(direction == "out") {
            $(transactionAmount).css('color', '#FF4348');
            $(transactionAmount).text('-'+value.amount);
        }

        $(transactionFullName).text(fullName);
        $(transactionFromPhone).text(value.phone);
        $(transactionDate).text(returnDateFormat(value.date, '/'));
        $(transactionMessage).text(value.message);
        $(transactionId).text(sTransactionID);

        $(parent).prepend(clone);
    }
}

// Return function *************************************************************************

function returnDateFormat(iTimestamp, sSpace) {
    let date = new Date(iTimestamp * 1000);
    let day = date.getDate();
    let month = date.getMonth()+1;
    let year = date.getFullYear();

    month = (month < 10 ? "0" : "") + month;
    day = (day < 10 ? "0" : "") + day;

    let fullDate = day + sSpace + month + sSpace + year
    return fullDate;
}

// Custom select JS Fiddle **************************************************

        // Iterate over each select element
        $('select').each(function () {

            // Cache the number of options
            let $this = $(this)
            let numberOfOptions = $(this).children('option').length;

            // Hides the select element
            $this.addClass('s-hidden');

            // Wrap the select element in a div
            $this.wrap('<div class="select"></div>');

            // Insert a styled div to sit over the top of the hidden select element
            $this.after('<div class="styledSelect"></div>');

            // Cache the styled div
            var $styledSelect = $this.next('div.styledSelect');

            // Show the first select option in the styled div
            $styledSelect.text($this.children('option').eq(0).text());

            // Insert an unordered list after the styled div and also cache the list
            var $list = $('<ul />', {
                'class': 'options'
            }).insertAfter($styledSelect);

            // Insert a list item into the unordered list for each select option
            for (var i = 0; i < numberOfOptions; i++) {
                $('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
            }

            // Cache the list items
            var $listItems = $list.children('li');

            // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
            $styledSelect.click(function (e) {
                e.stopPropagation();
                $('div.styledSelect.active').each(function () {
                    $(this).removeClass('active').next('ul.options').hide();
                });
                $(this).toggleClass('active').next('ul.options').toggle();
            });

            // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
            // Updates the select element to have the value of the equivalent option
            $listItems.click(function (e) {
                e.stopPropagation();
                $styledSelect.text($(this).text()).removeClass('active');
                $this.val($(this).attr('rel'));
                $list.hide();
                /* alert($this.val()); Uncomment this for demonstration! */
            });

            // Hides the unordered list when clicking outside of it
            $(document).click(function () {
                $styledSelect.removeClass('active');
                $list.hide();
            });

        });