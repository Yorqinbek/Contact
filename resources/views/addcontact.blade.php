<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Contacts</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<h1 class="text-center">Add contact</h1>
<br>
<div class="container">

    {{-- Validation error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add contact error --}}
    @if(session()->get('fail'))
        <div class="alert alert-danger">
            {{ session()->get('fail') }}
        </div>
    @endif


    <form role="form" method="post" action="/addcontact">
        @csrf
        <div class="form-group">
            <label class="col">Name</label>
            <div class="col">
                <div class="input-group">
                    <input type="text" name="first_name" class="form-control" placeholder="Name">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col">Phone number</label>
            <div class="col" id="contactrow">
                <div class="input-group">
                    <input type="text" name="phone[]" class="form-control" placeholder="Phone Number">
                    <div class="input-group-btn">
                        <button id="addcontact" class="btn btn-success" type="button">+</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col">Email adress</label>
            <div class="col" id="emailrow">
                <div class="input-group">
                    <input type="text" name="email[]" class="form-control" placeholder="Email adress">
                    <div class="input-group-btn">
                        <button id="addemail" class="btn btn-success" type="button">+</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col text-right">
                <button id="addemail" class="btn btn-primary" type="submit">Add contact</button>
            </div>
        </div>


    </form>


</div>
<script>

    $(document).ready(function () {

        //input index variable
        var contact_number = 0;
        var email_number = 0;


        //add dynamicaly new input phone
        $("#addcontact").click(function () {

            ++contact_number;
            var html = '';
            html +=
                '<div class="input-group" id="newcontactdiv" style="margin-top:10px;">';
            html += '<input type="text" name="phone[]" class="form-control" placeholder="Phone number"><div class="input-group-btn"><button id="removeRow" class="btn btn-danger " type="button">-</button></div>';
            $('#contactrow').append(html);
        });

        //add dynamicaly new input email
        $("#addemail").click(function () {

            ++email_number;
            var html = '';
            html +=
                '<div class="input-group" id="newemaildiv" style="margin-top:10px;">';
            html += '<input type="text" name="email[]" class="form-control" placeholder="Phone number"><div class="input-group-btn"><button id="removeRow" class="btn btn-danger " type="button">-</button></div>';
            $('#emailrow').append(html);
        });
    });


    //remove this phone input
    $(document).on('click', '#removeRow', function () {
        $(this).closest('#newcontactdiv').remove();
    });

    //remove this email input
    $(document).on('click', '#removeRow', function () {
        $(this).closest('#newemaildiv').remove();
    });

</script>
</body>

</html>
