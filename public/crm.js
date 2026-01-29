// Select box change handling
document.addEventListener('DOMContentLoaded', function () {
    const selectBoxes = document.querySelectorAll('select');
    selectBoxes.forEach(selectBox => {
        selectBox.addEventListener('change', function () {
            if (this.id === 'lead_day') {
                const fromDateContainer = document.getElementById('from_date_container');
                const toDateContainer = document.getElementById('to_date_container');
                if (this.value === 'custome') {
                    fromDateContainer.style.display = 'block';
                    toDateContainer.style.display = 'block';
                } else {
                    fromDateContainer.style.display = 'none';
                    toDateContainer.style.display = 'none';
                }
            }
        });
    });
});

// Set minimum date for the next date field
document.addEventListener('DOMContentLoaded', function() {
    var nextDateInput = document.getElementById('next_date');
    var today = new Date().toISOString().split('T')[0];
    nextDateInput.setAttribute('min', today);
});





function Category(value) {
    $('.custome, .common').hide();
    $('#error-Template').text('');

    if (value === 'common') {
        $('.common').show();
    } else if (value === 'custome') {
        $('.custome').show();
    } else {
        $('#error-Template').text('Please select a valid template type.');
    }
}

$(document).ready(function() {
    $('.custome, .common').hide();
});

// Sync phone code with country
function syncPhoneCode() {
    var selectedCountry = document.getElementById('country-select').value;
    var phoneCode = document.querySelector(`#country-select option[value="${selectedCountry}"]`).getAttribute('data-phonecode');
    $('#phone-code-select').val(phoneCode);
}


// Toggle reference name visibility based on role
function toggleReferenceName(value) {
    const referenceNameContainer = document.getElementById('reference-name-container');
    const blank = document.getElementById('blank');
    if (value === '3') {
        referenceNameContainer.style.display = 'block';
        blank.style.display = '{{ Auth::user()->hasRole("BDE") ? "block" : "none" }}';
    } else {
        referenceNameContainer.style.display = 'none';
        blank.style.display = '{{ Auth::user()->hasRole("BDE") ? "none" : "block" }}';
    }
}

// Bulk action on leads
$(document).ready(function() {
    // Store URLs and CSRF token in JavaScript variables
    var bulkUpdateUrl = "{{route('lead.bulkUpdate')}}";
    var csrfToken = "{{ csrf_token() }}";

    // Select all leads when 'select-all' checkbox is toggled
    $('#select-all').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.lead-checkbox').prop('checked', isChecked);
    });

    // Handle bulk action dropdown changes
    $('#bulk-action').on('change', function() {
        var action = $(this).val();
        var selectedLeads = $('.lead-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (action && selectedLeads.length > 0) {
            swal({
                title: "Are you sure?",
                text: "This will change the status of the selected leads.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willUpdate) => {
                if (willUpdate) {
                    // Send AJAX request
                    $.post(bulkUpdateUrl, {
                        _token: csrfToken,
                        action: action,
                        selectedLeads: selectedLeads
                    }, function(response) {
                        if (response.success) {
                            swal("Status updated successfully!", { icon: "success" }).then(() => {
                                location.reload();
                            });
                        } else {
                            swal("Error updating status.", { icon: "error" });
                        }
                    }).fail(function() {
                        swal("An error occurred while processing your request.", { icon: "error" });
                    });
                }
            });
        } else {
            swal("Please select at least one lead.", { icon: "warning" });
            $(this).val('');
        }
    });
});


// Initialize Select2 for multiple select
$(document).ready(function(){
    $('.select-2-multiple').select2({ theme: 'bootstrap-5' });
});


$(window).on('load', function() {
    function limitCharacters(text, limit) {
        if (text.length > limit) {
            return text.substring(0, limit) + '...';
        } else {
            return text;
        }
    }

    var wordLimit = 20; // Set your character limit here

    $('.lead-name').each(function() {
        var $this = $(this);
        var fullText = $this.text().trim();  // Trim any unnecessary spaces
        
        if (fullText.length > 0) {
            var limitedText = limitCharacters(fullText, wordLimit);
            $this.text(limitedText);
            $this.attr('title', fullText); // Optionally set the full text as a tooltip
        } else {
            console.log('No text found in this element.');
        }
    });
});

function submitFollowupForm(value) {
    var followupSelect = document.getElementById('followup');
    followupSelect.value = value;
    document.getElementById('filterForm').submit();
}



$(document).ready(function() {
    var table = $('#search_table').DataTable();
    $(".datepicker").datepicker( {
        maxDate:0,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        onClose: function(selectedDate) {
            table.draw();}});
    $('#min_create, #max_create, #min_update, #max_update').keyup(function() { table.draw(); });
    $('#min_create, #max_create, #min_update, #max_update').click( function() { table.draw(); });
    $('#min_create').datepicker().bind('onClose', function(){ table.draw(); });
});

function sefcsog() {
    document.querySelectorAll('.lead-name, .lead-city, .lead-country').forEach(function (element) {
        element.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            // Safely get elements by matching id
            var nameElement = document.querySelector(`.lead-name[data-id="${id}"]`);
            var cityElement = document.querySelector(`.lead-city[data-id="${id}"]`);
            var countryElement = document.querySelector(`.lead-country[data-id="${id}"]`);
            // Debug logs
            console.log('Clicked element ID:', id);
            console.log('Name Element:', nameElement);
            console.log('City Element:', cityElement);
            console.log('Country Element:', countryElement);

            var name = nameElement?.getAttribute('data-name') || '';
            var city = cityElement?.getAttribute('data-city') || '';
            var country = countryElement?.getAttribute('data-country') || '';

            var query = name;
            if (city.trim()) query += ` ${city}`;
            if (country.trim()) query += ` ${country}`;

            if (query.trim()) {
                window.open(`https://www.google.com/search?q=${encodeURIComponent(query)}`, '_blank');
            }
        });
    });
}

$(document).ready(function() {
    console.log('DOM fully loaded and parsed');
    sefcsog()
});