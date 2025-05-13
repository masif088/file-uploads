<!DOCTYPE html>
<html>
<head>
    <title>{{ $upload->title ?? 'Gallery' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .left-preview {
            flex: 1;
            background: #f9f9f9;
            padding: 20px;
            overflow-y: auto;
            text-align: center;
            display: block;
            flex-wrap: wrap;
            justify-content: center;
            align-items: start;
            flex-direction: column;
            align-content: center;
        }

        .left-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .right-thumbnails {
            width: 200px;
            background: #fff;
            padding: 20px 10px;
            border-left: 1px solid #ccc;
            overflow-y: auto;
        }

        .thumbnail {
            margin-bottom: 15px;
            cursor: pointer;
        }

        .thumbnail img {
            width: 100%;
            border-radius: 6px;
            border: 2px solid transparent;
            transition: transform 0.2s ease, border 0.2s ease;
        }

        .thumbnail img:hover {
            transform: scale(1.05);
            border-color: #007BFF;
        }

        .copy-button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
        }

        .copy-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>



<body>

<div style="position: absolute; top: 0; width: 100%; padding: 20px; background: #fff; text-align: center; border-bottom: 1px solid #ccc; font-size: 20px; font-weight: bold;">
    {{ $upload->title ?? 'Gallery' }}
</div>

<div style="margin-top: 70px; display: flex; height: calc(100vh - 70px); overflow: hidden;width: 100%">
    <div class="left-preview">
        <img id="mainImage" src="{{ asset($upload->details->first()->slug ?? '') }}" alt="{{ $upload->details->first()->title }}">

        <div class="title-container" id="mainTitle">
            {{ $upload->details->first()->title }}
        </div>

{{--        <button id="copyLinkButton" class="copy-button" onclick="copyLink()">Copy Link</button>--}}
    </div>

    <div class="right-thumbnails">
        @foreach ($upload->details as $detail)
            <div class="thumbnail">
                <img src="{{ asset($detail->slug) }}" alt="{{ $detail->title }}" onclick="changeMainImage(this)">
            </div>
        @endforeach
    </div>
</div>


<script>
    function changeMainImage(el) {
        const mainImage = document.getElementById('mainImage');
        const mainTitle = document.getElementById('mainTitle');
        const copyLinkButton = document.getElementById('copyLinkButton');
        mainImage.src = el.src;
        mainImage.alt = el.alt;
        mainTitle.innerHTML = el.alt;
    }

    function copyLink() {
        const mainImage = document.getElementById('mainImage');
        const linkToCopy = window.location.href+'/' + mainImage.alt;

        // Create a temporary input element to copy the text
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = linkToCopy;
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Alert or notify the user
        alert('Link copied: ' + linkToCopy);
    }
</script>

</body>
</html>
