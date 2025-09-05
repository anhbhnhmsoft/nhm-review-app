@php
    if (isset($file_type)){
        if (str_contains($file_type, 'image')){
            $type = "image";
        }else if (str_contains($file_type, 'video')){
            $type = "video";
        }
    }
@endphp
<a
    @if(!$last)
        data-fancybox
        @if($type == "image")
            data-src="{{\App\Utils\HelperFunction::generateURLImagePath($path)}}"
        @elseif($type == "video")
            data-src="{{\App\Utils\HelperFunction::generateURLVideoPath($path)}}"
        @endif
    @endif
    class="cursor-pointer relative"
>
    @if($type == "image")
        <img alt="{{$slug}}" src="{{\App\Utils\HelperFunction::generateURLImagePath($path)}}"
             class="w-full h-full shadow-md object-cover rounded-md"/>
    @elseif($type == "video")
        <video class="w-full h-full object-cover rounded-md" controls>
            <source src="{{\App\Utils\HelperFunction::generateURLVideoPath($path)}}"
                    type="{{ $file_type }}">
            Your browser does not support the video tag.
        </video>
    @endif
    @if($last)
        <div class="absolute inset-0 rounded-md flex items-center justify-center"
             style="background-color: rgba(0, 0, 0, 0.3);">
            <span class="text-white text-lg capitalize font-bold">+ {{$total_files}} Phương tiện</span>
        </div>
    @else
        <div
            class="absolute inset-0 rounded-md bg-black opacity-0 hover:opacity-30 transition-opacity duration-300"></div>
    @endif
</a>
