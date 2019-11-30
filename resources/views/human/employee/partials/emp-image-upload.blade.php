<div class="emp-image" id="emp-image">

    <form id="image-upload-form" action="{!! url('employee/image/upload') !!}"  method="post" accept-charset="utf-8" enctype="multipart/form-data">
        {{ csrf_field() }}

        <input type="hidden" name="employee_personal_id_for_image" id="employee_personal_id_for_image"/>

    <table class="table table-bordered">
        <tbody>
        <tr class="table-primary">
            <td>Employee Name : </td>
            <td id="emp_name_for_photo"></td>
            <td>Employee ID</td>
            <td id="emp_id_for_photo"></td>
        </tr>

        <tr>
            <td>Upload Employee Photo</td>
            <td>
                <div class="imageupload">
                    <div class="file-tab">
                        <label class="btn btn-default btn-file">
                            <span>Browse</span>
                            <!-- The file is stored here. -->
                            <input type="file" name="photo-image" id="photo-image">
                        </label>
                        <button type="button" class="btn btn-default">Remove</button>
                    </div>
                </div>

            </td>

            <td>Upload Signature Photo</td>
            <td>
                <div class="imageupload">
                    <div class="file-tab">
                        <label for="sign-image" class="btn btn-default btn-file">
                            <span>Browse</span>
                            <!-- The file is stored here. -->
                            <input type="file" name="sign-image" id="sign-image">
                        </label>
                        <button type="button" class="btn btn-default">Remove</button>
                    </div>
                </div>

            </td>
        </tr>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4"><button type="submit" id="btn-personal" class="btn btn-primary btn-image-post">Submit</button></td>
            </tr>
        </tfoot>

    </table>
    </form>
</div>
<script>
    $('.imageupload').imageupload({
        allowedFormats: [ "jpg", "jpeg", "png" ],
        previewWidth: 250,
        previewHeight: 250,
        maxFileSizeKb: 2048
    });
</script>
