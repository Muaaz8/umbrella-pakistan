
            var isAdmin = true;
            var roomId = false;
            var agentId = false;
            var agentUrl, visitorUrl, sessionId, shortAgentUrl, shortVisitorUrl, agentBroadcastUrl, viewerBroadcastLink;

            jQuery(document).ready(function ($) {

                $('#saveLink').on('click', function () {
                    generateLink();
                    var datetime = ($('#datetime').val()) ? new Date($('#datetime').val()).toISOString() : '';
                    $.ajax({
                        type: 'POST',
                        url: lsRepUrl + '/server/script.php',
                        data: {'type': 'scheduling', 'agentId': agentId, 'agent': $('#names').val(), 'agenturl': agentUrl, 'visitor': $('#visitorName').val(), 'visitorurl': visitorUrl,
                            'password': $('#roomPass').val(), 'session': sessionId, 'datetime': datetime, 'duration': $('#duration').val(), 'shortVisitorUrl': shortVisitorUrl, 'shortAgentUrl': shortAgentUrl}
                    })
                            .done(function (data) {
                                if (data == 200) {
                                    alert('Successfully saved');
                                } else {
                                    alert(data);
                                }
                            })
                            .fail(function () {
                                console.log('failed');
                            });
                });

                $('#generateLink').on('click', function () {
                    generateLink(false);
                    window.open(agentUrl);
                    $('#agentUrl').html('Agent URL:<br/>' + agentUrl);
                    $('#visitorUrl').html('Visitor URL:<br/>' + visitorUrl);
                });

                $('#generateBroadcastLink').on('click', function () {
                    generateLink(true);
                    window.open(agentUrl);
                    $('#agentUrl').html('Agent URL:<br/>' + agentUrl);
                    $('#visitorUrl').html('Visitor URL:<br/>' + visitorUrl);
                });

                var d = new Date();
                $('#datetime').datetimepicker({
                    timeFormat: 'h:mm TT',
                    stepHour: 1,
                    stepMinute: 15,
                    controlType: 'select',
                    hourMin: 8,
                    hourMax: 21,
                    minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), 0),
                    oneLine: true
                });
            });
        <script src="https://suunnoo.com/js/loader.v2.js" data-source_path="https://suunnoo.com/" ></script>

        // <script src="{{asset('asset_admin/js/video/loader.v2.js')}}" data-source_path="https://suunnoo.com/" ></script>