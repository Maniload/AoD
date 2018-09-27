<html>
<head>
    <style>
        html {
            min-height: 100%;
            position: relative;
        }
        body {
            display: grid;
            margin: 0;
            background-color: #333333;
            font-family: 'Open Sans', sans-serif;
        }
        p, label {
            padding: 0 10px 0 10px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            color: white;
            font-size: 14pt;
            font-weight: bolder;
            text-wrap: avoid;
            text-decoration: none;
        }
        h1, h2, h3, h4, h5 {
            color: white;
            text-align: center;
            width: 100%;
            margin: 10px 0 10px 0;
        }
        a {
            color: white;
        }
        .wrapper {
            display: flex;
            text-align: center;
            width: 100%;
            float: left;
        }
        .episode {
            display: inline-block;
            padding: 10px;
            position: relative;
            box-sizing: border-box;
            width: 100%;
        }
        .episode-inner {
            position: relative;
            padding-top: 56.25%;
            box-shadow: #222222 0px 0px 20px 5px;
            overflow: hidden;
        }
        .episode-container {
            text-align: start;
            width: 25%;
            max-height: 100vh;
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .episode-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 1px;
            text-align: center;
            width: -webkit-fill-available;
            line-height: 100%;
        }
        .cover {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            object-fit: cover;
            margin-left: 50%;
            transform: translateX(-50%);
        }
        .player {
            width: 100%;
            height: 100%;
        }
        .player-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            display: block;
        }
        .player-container {
            width: 80%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .logo {
            width: 50%;
            height: auto;
        }
        .logo-container {
            position: absolute;
            width: 80%;
            top: 50%;
            transform: translate(0, -50%);
            z-index: -1;
        }
        .menu-container {
            padding: 0 10px 0 10px;
        }
        .description {
            text-align: start;
            font-size: 12pt;
            text-decoration: none;
            padding: 0 10px 0 10px;
            color: #EEEEEE;
            line-height: 14pt;
        }

        img {
            transition: width 0.5s;
        }
        .episode-inner:hover img {
            width: 110%;
        }

        .episode-overlay {
            transition: height 0.5s;
            height: 20%;
        }
        .episode-inner:hover .episode-overlay {
            height: 100%;
        }
        .episode-inner:hover p {
            white-space: initial;
            overflow: initial;
            line-height: initial;
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script src="jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script type="text/javascript">

        function play(url) {
            let player = document.getElementById("player");
            if (Hls.isSupported()) {
                let hls = new Hls();
                hls.loadSource("get_playlist.php?url=" + url);
                hls.attachMedia(player);
                hls.on(Hls.Events.MANIFEST_PARSED, function () {
                    video.play();
                });
            }
        }

    </script>
</head>
<body>
    <div class="wrapper">
        <div class="player-container" id="video">
            <div class="logo-container">
                <!-- Insert AoD logo -->
            </div>
            <video class="player" id="player"></video>
        </div>
        <div id="episode-container" class="episode-container"></div>
    </div>
    <script>
        let url = "<?php echo $_GET['url']; ?>";
        $.get("get_series.php", { url: url }).done(function(data) {

            for (let episode of data.episodes) {
                let container = $("<div>", { "class": "episode" });
                let innerContainer = $("<div>", { "class": "episode-inner" });
                let anchor = $("<a>", { href: "javascript:play(\"" + url + "\");" });
                anchor.append($("<img>", { "class": "cover", src: episode.image }));
                let overlay = $("<div>", { "class": "episode-overlay" });
                overlay.append($("<p>", { html: episode.title }));
                overlay.append($("<p>", { html: episode.description, "class": "description" }));
                anchor.append(overlay);
                innerContainer.append(anchor);
                container.append(innerContainer);
                $("#episode-container").append(container);
            }

        });
    </script>
</body>
</html>