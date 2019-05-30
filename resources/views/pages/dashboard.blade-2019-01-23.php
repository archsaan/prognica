<!-- Set title -->
@section('title', 'Dashboad')

@extends('layouts.default')

<!-- this page CSS -->
@section('page-css')
{{ HTML::style('/vendors/bootstrap-datepicker/css/datepicker3.css') }}
{{ HTML::style('/vendors/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css') }}
{{ HTML::style('/vendors/select2/select2.css') }}
{{ HTML::style('/vendors/bootstrap-multiselect/bootstrap-multiselect.css') }}
{{ HTML::style('/vendors/bootstrap-tagsinput/bootstrap-tagsinput.css') }}
{{-- HTML::style('/vendors/bootstrap-colorpicker/css/bootstrap-colorpicker.css') --}}
{{-- HTML::style('/vendors/bootstrap-timepicker/css/bootstrap-timepicker.css') --}}
{{ HTML::style('/vendors/dropzone/css/basic.css') }}
{{ HTML::style('/vendors/dropzone/css/dropzone.css') }}
{{ HTML::style('/vendors/bootstrap-markdown/css/bootstrap-markdown.min.css') }}
{{ HTML::style('/vendors/summernote/summernote.css') }}
{{ HTML::style('/vendors/summernote/summernote-bs3.css') }}
{{ HTML::style('/vendors/codemirror/lib/codemirror.css') }}
{{ HTML::style('/vendors/codemirror/theme/monokai.css') }}

<!-- Cropper Plugin -->

{{ HTML::style('/vendors/viewer/style.css') }}
{{-- HTML::style('/vendors/cropper/dist/cropper.min.css') --}}
<style>
    .range-slider__range {
        -webkit-appearance: none;
        width: calc(100% - (73px));
        height: 10px;
        border-radius: 5px;
        background: #d7dcdf;
        outline: none;
        padding: 0;
        margin: 0;
    }

    /* custom thumb */
    .range-slider__range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #2c3e50;
        cursor: pointer;
        -webkit-transition: background .15s ease-in-out;
        transition: background .15s ease-in-out;
    }

    .range-slider__range::-webkit-slider-thumb:hover {
        background: #1abc9c;
    }

    .range-slider__range:active::-webkit-slider-thumb {
        background: #1abc9c;
    }

    .range-slider__range::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border: 0;
        border-radius: 50%;
        background: #2c3e50;
        cursor: pointer;
        -webkit-transition: background .15s ease-in-out;
        transition: background .15s ease-in-out;
    }

    .range-slider__range::-moz-range-thumb:hover {
        background: #1abc9c;
    }

    .range-slider__range:active::-moz-range-thumb {
        background: #1abc9c;
    }

    .range-slider__range:focus::-webkit-slider-thumb {
        -webkit-box-shadow: 0 0 0 3px #fff, 0 0 0 6px #1abc9c;
        box-shadow: 0 0 0 3px #fff, 0 0 0 6px #1abc9c;
    }

    /* custom label */
    .range-slider__value {
        display: inline-block;
        position: relative;
        width: 60px;
        color: #fff;
        line-height: 20px;
        text-align: center;
        border-radius: 3px;
        background: #2c3e50;
        padding: 5px 10px;
        margin-left: 8px;
    }

    .range-slider__value:after {
        position: absolute;
        top: 8px;
        left: -7px;
        width: 0;
        height: 0;
        border-top: 7px solid transparent;
        border-right: 7px solid #2c3e50;
        border-bottom: 7px solid transparent;
        content: '';
    }

    /* custom track */
    ::-moz-range-track {
        background: #d7dcdf;
        border: 0;
    }

    /* remove border */
    input::-moz-focus-inner, input::-moz-focus-outer {
        border: 0;
    }
</style>
@endsection

@section('sidebar')
@include('partials.sidebar')
@endsection

@section('content')
<header class="page-header">
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs docs-buttons">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-pan t-icon-disabled" data-method="setDragMode" data-option="move" data-toggle="tooltip" data-placement="bottom" title="Pan"><i class="fa fa-arrows"></i> </button>            
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-adjust t-icon-disabled dropdown-toggle" data-toggle="tooltip" data-placement="top" title="Adjust"><i class="fa fa-sun-o"></i> </button>            
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-negative t-icon-disabled" data-toggle="tooltip" data-placement="bottom" title="Invert"><i class="fa fa-adjust"></i> </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-flip-horizontal t-icon-flipHorizontal t-icon-disabled" data-method="scaleX" data-option="-1" title="Flip Horizontally" class="docs-tooltip" data-toggle="tooltip" data-placement="bottom">
                <i class="fa fa-arrows-h"></i>
            </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-flip-vertical t-icon-flipVertical t-icon-disabled" data-method="scaleY" data-option="-1" title="Flip Vetically" class="docs-tooltip" data-toggle="tooltip" data-placement="bottom">
                <i class="fa fa-arrows-v"></i>
            </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-zoom-in t-icon-disabled" data-method="zoom" data-option="0.1" data-toggle="tooltip" data-placement="bottom" title="Zoom In"><i class="fa fa-search-plus"></i> </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-zoom-out t-icon-disabled" data-method="zoom" data-option="0.1" data-toggle="tooltip" data-placement="bottom" title="Zoom Out"><i class="fa fa-search-minus"></i> </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-magnify t-icon-disabled" data-toggle="tooltip" data-placement="bottom" title="Magnify"><i class="fa fa-search"></i> </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-rotate-right t-icon-disabled" data-toggle="tooltip" data-placement="bottom" title="Rotate Right"><i class="fa fa-rotate-right"></i> </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-rotate-left t-icon-disabled" data-toggle="tooltip" data-placement="bottom" title="Rotate Left"><i class="fa fa-rotate-left"></i> </button>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default t-icon-fit-to-screen t-icon-disabled" data-method="reset" data-toggle="tooltip" data-placement="bottom" title="Reset"><i class="fa fa-refresh"></i> </button>
            <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
            <label class="mb-xs mt-xs mr-xs btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" data-placeholder="allowed file extensions: jpg,png,gif" data-buttonText="Upload a image" data-iconName="fa fa-upload" data-buttonName="btn-warning" pattern="^.*\.(jpg|jpeg|gif|png)$" required="">
                <span class="docs-tooltip" data-toggle="tooltip" style="color: white;">
                    <span class="fa fa-cloud-upload" style="color: white;"></span> Upload File
                </span>
            </label>
            <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" class="docs-tooltip" data-toggle="tooltip">
                <span class="docs-tooltip" data-toggle="tooltip" style="color: white;">
                    <span class="fa fa-file-text-o" style="color: white;"></span> Download Report
                </span>
            </button>
        </ol>
        <div class="dropdown">
            <ul class="dropdown-menu adjust-slider">
                <li>
                    <div class="range-slider" style="max-width: 100%;">
                        <input class="range-slider__range" type="range" value="50" min="0" max="500">
                    </div>
                </li>
            </ul>
        </div>
        &nbsp;&nbsp;&nbsp;
    </div>
</header>
<div class="row">
    <div class="col-xs-10 col-xs-push-1">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (session($msg))
        <div class="alert alert-{{ $msg }}">{{ session($msg) }}</div>
        @endif
        @endforeach
    </div>
</div>
<!-- start: page -->
<div class="row">
    <div class="col-xs-12">
        <section class="panel upload_image">
            <div class="panel-body">
                {{ FORM::open(['url' => 'upload_image', 'method' => 'post', 'files' => 'true', 'id' => 'UploadUltraImage', 'class' => 'dropzone dz-square']) }}                
                <div class="img-container">
                    @if(old('image'))
                    {{ HTML::image('images/upload/banner/'.old('image'), old('title'), array('id'=>'image'))}}
                    @else
                    <img id="image" src="#" alt="">
                    <label class="init-upload" for="inputImage" title="Upload image file" style="font-size: 30px; padding-top: 20%;">
                        <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" data-placeholder="allowed file extensions: jpg,png,gif" data-buttonText="Upload a image" data-iconName="fa fa-upload" data-buttonName="btn-warning" pattern="^.*\.(jpg|jpeg|gif|png)$">
                        <div class="dz-default dz-message">
                            <span>Drop files here to upload</span>
                        </div>
                    </label>
                    @endif
                </div>
                <div>

                </div>
                {{ FORM::close() }}
            </div>
        </section>
        <!-- Canvas -->
        <section class="panel dv-demo">
            <div class="panel-body">
                <section class="base-menu container-fluid p-0">
                </section>
                <section class="base-body container-fluid p-0">
                    <div class="d-flex justify-content-between">
                        <div class="dv-selection-area"></div>
                        <div class="dv-preview d-flex flex-wrap justify-content-center" style="min-height: 70vh; min-width: 100%;">
                            <div class="preview-window m-1 border-active w-100 mw-100 h-100">
                                <div id="dv-canvas-0" class="dv-canvas w-100 h-100" data-loader-width="0"></div>
                            </div>
<!--                            <div class="preview-window m-1">
                                <div class="dv-image-classification"></div>
                                <div class="dv-image-left-top"></div>
                                <div class="dv-image-right-top"></div>
                                <div class="dv-image-left-bottom"></div>
                                <div class="dv-image-right-bottom"></div>
                                <div class="dv-image-scroll"></div>
                                <div class="dv-image-ruler"></div>
                                <div class="dv-image-description"></div>
                                <div id="dv-canvas-1" class="dv-canvas w-100 h-100" data-loader-width="0"></div>
                            </div>
                            <div class="preview-window m-1">
                                <div class="dv-image-classification"></div>
                                <div class="dv-image-left-top"></div>
                                <div class="dv-image-right-top"></div>
                                <div class="dv-image-left-bottom"></div>
                                <div class="dv-image-right-bottom"></div>
                                <div class="dv-image-scroll"></div>
                                <div class="dv-image-ruler"></div>
                                <div class="dv-image-description"></div>
                                <div id="dv-canvas-2" class="dv-canvas w-100 h-100" data-loader-width="0"></div>
                            </div>
                            <div class="preview-window m-1">
                                <div class="dv-image-classification"></div>
                                <div class="dv-image-left-top"></div>
                                <div class="dv-image-right-top"></div>
                                <div class="dv-image-left-bottom"></div>
                                <div class="dv-image-right-bottom"></div>
                                <div class="dv-image-scroll"></div>
                                <div class="dv-image-ruler"></div>
                                <div class="dv-image-description"></div>
                                <div id="dv-canvas-3" class="dv-canvas w-100 h-100" data-loader-width="0"></div>
                            </div>-->
                        </div>
                    </div>
                </section>
            </div>
        </section>
        <!-- /Canvas -->
    </div>
</div>
<section class="panel">
    <div class="panel-body">
        <p class="text-primary text-center">
            Prognica for Ultrasound is significant in differentiating cystic from solid breast masses. </br>
            It also helps in detecting suspicious breast masses, allowing clinicians to see more and do more,
            at an earlier stage, to improve patient outcomes.
        </p>
        <p class="text-dark text-center">
            <i class="fa fa-download" aria-hidden="true"></i>&nbsp;&nbsp; Please <a href="#"><b>click here</b></a> to download sample file.
        </p>
    </div>
</section>
<!-- /image cropping -->


<!-- end: page -->
@endsection

<!-- this page JS -->
@section('page-JS')

{{ HTML::script('/vendors/jquery-ui-touch-punch/jquery.ui.touch-punch.js') }}
{{ HTML::script('/vendors/select2/select2.js') }}
{{ HTML::script('/vendors/bootstrap-multiselect/bootstrap-multiselect.js') }}
{{ HTML::script('/vendors/jquery-maskedinput/jquery.maskedinput.js') }}
{{ HTML::script('/vendors/bootstrap-tagsinput/bootstrap-tagsinput.js') }}
{{ HTML::script('/vendors/fuelux/js/spinner.js') }}
{{ HTML::script('/vendors/bootstrap-markdown/js/markdown.js') }}
{{ HTML::script('/vendors/bootstrap-markdown/js/to-markdown.js') }}
{{ HTML::script('/vendors/bootstrap-markdown/js/bootstrap-markdown.js') }}
{{ HTML::script('/vendors/codemirror/lib/codemirror.js') }}
{{ HTML::script('/vendors/codemirror/addon/selection/active-line.js') }}
{{ HTML::script('/vendors/codemirror/addon/edit/matchbrackets.js') }}
{{ HTML::script('/vendors/codemirror/mode/javascript/javascript.js') }}
{{ HTML::script('/vendors/codemirror/mode/xml/xml.js') }}
{{ HTML::script('/vendors/codemirror/mode/htmlmixed/htmlmixed.js') }}
{{ HTML::script('/vendors/codemirror/mode/css/css.js') }}
{{ HTML::script('/vendors/summernote/summernote.js') }}
{{ HTML::script('/vendors/bootstrap-maxlength/bootstrap-maxlength.js') }}
{{ HTML::script('/vendors/ios7-switch/ios7-switch.js') }}
<!-- Viewer -->
{{ HTML::script('/vendors/viewer/svg.min.js') }}
{{ HTML::script('/vendors/viewer/svg.draggable.js') }}
{{ HTML::script('/vendors/viewer/modernizr.min.js') }}
{{ HTML::script('/vendors/viewer/viewer.js') }}
<!-- elevatezoom-magnifier -->
{{ HTML::script('/vendors/elevatezoom-magnifier/jquery.elevatezoom.js') }}

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    $(document).ready(function () {
        // Import image
        var $inputImage = $('#inputImage');
        var URL = window.URL || window.webkitURL;
        var blobURL;
        var $image = $('#image');

        if (URL) {
            $inputImage.change(function () {

                var filesYes = this.files;
                var file;

                if (filesYes && filesYes.length) {
                    file = filesYes[0];
                    // Create a formdata object and add the files
                    var data = new FormData();
                    $.each(this.files, function (key, value)
                    {
                        data.append(key, value);
                    });

                    if (/^image\/\w+$/.test(file.type)) {
                        var data;
                        // Upload image via Ajax
                        $.ajax({
                            type: 'POST',
                            url: "{{ url('/upload_image') }}",
                            dataType: 'json',
                            async: false,
                            cache: false,
                            processData: false, // Don't process the files
                            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                            data: data,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.status == 1) {
                                    // Load Viewer
                                    DV.data({
                                        "20181122497600": {
                                            "id": "20181122497600",
                                            "name": "20181122497600-4jijcc9s",
                                            "orientation": "LPI",
                                            "imageSizeMm": 300,
                                            "patient": "anonymous",
                                            "date": "2019-01-17",
                                            "path": response.data.path,
                                            "weights": [],
                                            "annotations": [],
                                            "series": {
                                                "001": {
                                                    "orig": [response.data.filename]
                                                }
                                            },
                                            "thumbanil": "thumbnail.png",
                                            "debugInfo": []
                                        },
                                        "20181122173300": {
                                            "id": "20181122173300",
                                            "name": "20181122173300-8rfjde8",
                                            "orientation": "LPI",
                                            "imageSizeMm": 300,
                                            "patient": "anonymous",
                                            "date": "2019-01-17",
                                            "path": response.data.path,
                                            "weights": [],
                                            "annotations": [],
                                            "series": {
                                                "001": {
                                                    "orig": [response.data.filename]
                                                }
                                            },
                                            "thumbanil": "thumbnail.png",
                                            "debugInfo": []
                                        }
                                    });
                                    DV.openSeries('20181122497600', '001', 0);
                                    $('.upload_image').hide();
                                    $('.dv-demo').show();
                                } else {
                                    alert(response.Response)
                                }
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        });

                    } else {
                        window.alert('Please choose an image file.');
                    }
                }
            });
        } else {
            $inputImage.prop('disabled', true).parent().addClass('disabled');
        }
    });
</script>
@endsection