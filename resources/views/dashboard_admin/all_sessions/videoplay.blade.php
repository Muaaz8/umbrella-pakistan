@if ($videoLink=="Not Found Recording")
    <h1>Not Found Recording</h1>
@else
    <video style="height:100%; width:100%;" autoplay controls>
        <source src="https://agora-videos.s3.us-east-2.amazonaws.com/{{$videoLink}}" type="video/mp4">
    </video>
@endif


