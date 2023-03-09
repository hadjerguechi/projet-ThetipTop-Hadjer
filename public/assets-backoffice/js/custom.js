/** Form input */

$(document).ready(function () {

    $(".form-check").each(function () {

        let inputContainer = $(this);
        let labelText = inputContainer.find(".form-check-label").text();
        inputContainer.find(".form-check-label").text("");

        let labelDiv = inputContainer.find(".form-check-label");
        let input = inputContainer.find("input")

        let span = $([
            '<span class="form-check-sign">' + labelText + '</span>'
        ].join(''));

        labelDiv.append(input);
        labelDiv.append(span);

    });

});


/** Photo uploader */

/** Datepicker */

$('.datepicker').datetimepicker({
    format: 'DD/MM/YYYY',
});


/** Data Table */

$(document).ready(function () {
    $('.data-table').DataTable({
        "order": [],
        "language": {
            "lengthMenu": "Afficher _MENU_ lignes par page",
            "zeroRecords": "Aucun resultat",
            "info": "Page _PAGE_ of _PAGES_",
            "search": "Recherche",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                "first": "Début",
                "last": "Fin",
                "next": "Suivant",
                "previous": "Précédent"
            },
            "aria": {
                "sortAscending": ": Trier par ordre croissant",
                "sortDescending": ": Trier par order décroissant"
            }
        }
    });
});


/** Full calendar */


/* initialize the calendar
-----------------------------------------------------------------*/
$(document).ready(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var calendarEl = $('#calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        eventClick: function (event, jsEvent, view) {
            $('#modalTitle').html(event.title);
            $('#modalBody').html(event.description);
            $('#calendarModal').modal();
        },

      
        /*
        events: [
            {
                title: 'Meeting',
                start: new Date(y, m, d, 10, 30),
                className: 'fc-aaaa'
            },
            {
                title: 'Lunch',
                start: new Date(y, m, d, 12, 0),
                className: 'fc-primary',
                description: 'Eat Bro'
            },
            {
                title: 'Lunch',
                start: new Date(y, m, d, 12, 0),
                className: 'fc-success-solid',
                description: 'Lunch'
            },
            {
                title: 'aaa',
                start: new Date(y, m, d, 12, 0),
                className: 'fc-success-solid',
                description: 'Lunch'
            },
            {
                title: 'Birthday Party',
                start: new Date(y, m, d + 1, 19, 0),
                allDay: false,
                className: 'fc-success',
                description: 'Coba Googling dulu'
            },
            {
                title: 'Lunch',
                start: new Date(y, m, d + 6, 13, 30),
                allDay: false,
                className: 'fc-success-solid',
                description: 'Lunch'
            },
        ],
        */


    });


    calendar.render();

    calendar.refetchEvents()

});




/*** Publication  */






/** Tags  */

$(document).ready(function () {
    (function () {
        function formatRepo (repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__title'>" + repo.name + "</div></div>";
            return markup;
        }
        function formatRepoSelection (repo) {
            return repo.name || repo.id;
        }
        var $tagInput = $('input[name$="[tagsText]"]');
        function tags($input) {
            $input.attr('type', 'hidden');
            var $select = $('<select id="select_' + $input.attr("name") + '" name="select_' + $input.attr("name") + '" class="form-control" multiple="multiple" />');
            var data = $input.val().split(',');
            for(var v in data) {
                var value = $.trim(data[v]);
                $("<option />", {value: value, text: value}).attr("selected", true).appendTo($select);
            }
            $select.insertAfter($input);
            $select.select2({
                tags: true,
                tokenSeparators: [','],
                multiple: true,
                ajax: {
                    url: $input.data('ajax'),
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true,
                    escapeMarkup: function (markup) { return markup; }, 
                    minimumInputLength: 1,
                    templateResult: formatRepo, 
                    templateSelection: formatRepoSelection, 
                }
            });
            $select.on("change", function (e) {
                var selectedValues = $(this).val();
                $input.val(selectedValues.join(", "));
            });
        }
        if ($tagInput.length > 0) {
            tags($tagInput);
        }
    }());
});