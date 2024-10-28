</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="{{ asset('assets/js/dashboard_custom.js') }}"></script>

<script>
    <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function myFunction() {
        $.ajax({
            type: "POST",
            url: "/ReadAllNotifications",
            data: {
                check: "",
            },
            success: function(data) {

            },
        });
    }

    $("#unreadmsgs").click(function() {
        $.ajax({
            type: "POST",
            url: "/GetUnreadNotifications",
            data: {
                check: "",
            },
            success: function(data) {
                $('#notif').html('');

                $.each(data, function(key, note) {
                    var today = new Date();

                    var Christmas = new Date(note.created_at);

                    var diffMs = (today -
                    Christmas); // milliseconds between now & Christmas
                    var diffDays = Math.floor(diffMs / 86400000); // days
                    var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
                    var diffMins = Math.round(((diffMs % 86400000) % 3600000) /
                    60000); // minutes
                    var noteTime='';

                    if (diffDays <= 0) {

                        if (diffHrs <= 0) {
                            if (diffMins <= 0) {
                                noteTime='0 mint ago';
                            } else {
                                noteTime = diffMins + ' mints ago';
                            }
                        } else {
                            noteTime = diffHrs + ' hours ago';
                        }
                    } else {

                        if (diffDays == 1) {
                            noteTime = diffDays + ' day ago';
                        } else {
                            noteTime = diffDays + ' day ago';
                        }
                    }

                    $('#notif').append(
                        '<div class = "sec new">' +
                        '<a href="/ReadNotification/'+note.id+'" >' +
                        '<div class = "profCont">' +
                        '<img class = "profile" src = "{{ asset('assets/images/notifyuser.png') }}">' +
                        '</div>' +
                        '<div class="txt">' + note.text + '</div>' +
                        '<div class = "txt sub">' + noteTime + '</div>' +
                        '</a>' +
                        '</div>'
                    );
                });
            },
        });
    });
</script>
