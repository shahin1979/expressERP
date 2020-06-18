<div class="jumbotron" id="edit-product">
    <div class="row w-100">
        <div class="col-md-9">
            <div class="card border-info mx-sm-1 p-3">
                <div class="card border-info shadow text-info p-3 my-card" ><span class="text-center" aria-hidden="true">Product Update</span></div>

                <table class="table table-success table-responsive table-striped">
                    <tbody>

                        <tr>
                            <td><label for="name-for-edit" class="control-label">Name <span style="color: red">*</span></label></td>
                            <td colspan="3"><input id="name-for-edit" type="text" class="form-control" name="name-for-edit" value=""  autocomplete="off"></td>
                        </tr>

{{--                        <tr>--}}
{{--                            <td><label for="category_id" class="control-label">Category<span style="color: red">*</span></label></td>--}}
{{--                            <td>{!! Form::select('category_id', $categories , null , array('id' => 'category_id', 'class' => 'form-control')) !!}</td>--}}
{{--                            <td><label for="subcategory_id" class="control-label">Category<span style="color: red">*</span></label></td>--}}
{{--                            <td>{!! Form::select('subcategory_id', $subcategories , null , array('id' => 'subcategory_id', 'class' => 'form-control')) !!}</td>--}}
{{--                        </tr>--}}
                        <tr>
                            <td><label for="brand-id-for-update" class="control-label">Brand</label></td>
                            <td>{!! Form::select('brand-id-for-update', $brands , null , array('id' => 'brand-id-for-update', 'class' => 'form-control')) !!}</td>
                            <td><label for="unit-name-for-update" class="control-label">Unit<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('unit-name-for-update', $units , null , array('id' => 'unit-name-for-update', 'class' => 'form-control')) !!}</td>
                        </tr>

{{--                        <tr>--}}
{{--                            <td><label for="second_unit" class="control-label">Second Unit</label></td>--}}
{{--                            <td>{!! Form::select('second_unit', $units , null , array('id' => 'second_unit', 'class' => 'form-control','placeholder' => 'Second Unit...')) !!}</td>--}}
{{--                            <td><label for="third_unit" class="control-label">Third Unit</label></td>--}}
{{--                            <td>{!! Form::select('third_unit', $units , null , array('id' => 'third_unit', 'class' => 'form-control','placeholder' => 'Third Unit...')) !!}</td>--}}
{{--                        </tr>--}}

                        <tr>
                            <td><label for="size-id-for-update" class="control-label">Size</label></td>
                            <td>{!! Form::select('size-id-for-update', $sizes , null , array('id' => 'size-id-for-update', 'class' => 'form-control')) !!}</td>
                            <td><label for="color-id-for-update" class="control-label">Color</label></td>
                            <td>{!! Form::select('color-id-for-update', $colors , null , array('id' => 'color-id-for-update', 'class' => 'form-control')) !!}</td>
                        </tr>


                        <tr>
                            <td><label for="godown-id-for-update" class="control-label">Store<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('godown-id-for-update', $stores , null , array('id' => 'godown-id-for-update', 'class' => 'form-control')) !!}</td>
                            <td><label for="rack-id-for-update" class="control-label">Rack<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('rack-id-for-update', $racks , null , array('id' => 'rack-id-for-update', 'class' => 'form-control')) !!}</td>
                        </tr>



                        <tr>
                            <td><label for="model-id-for-update" class="control-label">Model<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('model-id-for-update', $models , null , array('id' => 'model-id-for-update', 'class' => 'form-control')) !!}</td>
                            <td><label for="tax-id-for-update" class="control-label">Tax<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('tax-id-for-update', $taxes , null , array('id' => 'tax-id-for-update', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="reorder-point-for-update" class="control-label">Reorder Level</label></td>
                            <td><input id="reorder-point-for-update" type="text" class="form-control" name="reorder-point-for-update" value="{!! 0 !!}" required></td>
                            <td><label for="expiry-date-for-update" class="control-label" style="text-align: left">Expiry Date</label></td>
                            <td><input id="expiry-date-for-update" type="text" class="form-control" name="expiry-date-for-update" value="{!! \Carbon\Carbon::now()->format('d-m-Y') !!}" readonly></td>
                        </tr>

                        <tr class="opening-info">
                            <td><label for="opening-qty-for-update" class="control-label">Opening Qty</label></td>
                            <td><input id="opening-qty-for-update" type="text" class="form-control" name="opening-qty-for-update" value="" required></td>
                            <td><label for="opening-value-for-update" class="control-label" style="text-align: left">Opening Value</label></td>
                            <td><input id="opening-value-for-update" type="text" class="form-control" name="opening-value-for-update" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="wholesale_price" class="control-label">Whole Sale Price Qty</label></td>
                            <td><input id="wholesale_price" type="text" class="form-control" name="wholesale_price" value="" required></td>
                            <td><label for="retail_price" class="control-label" style="text-align: left">Retail Price</label></td>
                            <td><input id="retail_price" type="text" class="form-control" name="retail_price" value=""></td>
                        </tr>


                        <tr>
                            <td><label for="description-short-for-update" class="control-label">Short Description</label></td>
                            <td>{!! Form::textarea('description-short-for-update',null,['id'=>'description-short-for-update','size' => '20x3','class'=>'field']) !!}</td>
                            <td><label for="description-long-for-update" class="control-label">Long Description</label></td>
                            <td>{!! Form::textarea('description-long-for-update',null,['id'=>'description-long-for-update','size' => '20x3','class'=>'field']) !!}</td>
                        </tr>



                    </tbody>

                    <input id="id-for-update" type="hidden" name="id-for-update"/>

                    <tfoot>
                        <tr>
                            <td colspan="4"><button type="submit" id="btn-product-update" class="btn btn-primary btn-product-update">Submit</button></td>
                        </tr>
                    </tfoot>


                </table>

{{--                <div class="text-info text-center mt-3"><label for="category_id" class="control-label">Category<span style="color: red">*</span></label></div>--}}
{{--                <div class="text-info text-center mt-2">{!! Form::select('category_id', $categories , null , array('id' => 'category_id', 'class' => 'form-control')) !!}</div>--}}
            </div>
        </div>

        <div class="col-md-3">

            <div class="card border-success mx-sm-1 p-3" style="background: #3A92AF;background: -webkit-linear-gradient(bottom left, #3A92AF 0%, #5CA05A 100%);background: -moz-linear-gradient(bottom left, #3A92AF 0%, #5CA05A 100%);
                        background: -o-linear-gradient(bottom left, #3A92AF 0%, #5CA05A 100%);
                            background: linear-gradient(to top right, #3A92AF 0%, #5CA05A 100%);">
                <div class="card border-success shadow text-success p-3 my-card"><span class="fa fa-eye" aria-hidden="true">Image</span></div>

                <div class="imageupload">
                    <div class="file-tab">
                        <label class="btn btn-success btn-file">
                            <span>Upload Photo</span>
                            <!-- The file is stored here. -->
                            <input type="file" name="product-image" id="product-image">
                        </label>
                        <button type="button" class="btn btn-default">Remove</button>
                    </div>
                </div>
            </div>

            <button type="submit" id="btn-image-update" class="btn btn-primary btn-color-update">Save Image</button>

        </div>

    </div>

</div>

<script>
    $(document).on('click', '.btn-product-update', function (e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var url = 'item/update/' + $('#id-for-update').val();

        // confirm then
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',

            data: {method: '_POST', submit: true, name:$('#name-for-edit').val(),
                brand_id:$('#brand-id-for-update').val(),
                unit_name:$('#unit-name-for-update').val(),
                size_id:$('#size-id-for-update').val(),
                color_id:$('#color-id-for-update').val(),
                model_id:$('#model-id-for-update').val(),
                tax_id:$('#tax-id-for-update').val(),
                godown_id:$('#godown-id-for-update').val(),
                rack_id:$('#rack-id-for-update').val(),
                reorder_point:$('#reorder-point-for-update').val(),
                expiry_date:$('#expiry-date-for-update').val(),
                opening_qty:$('#opening-qty-for-update').val(),
                opening_value:$('#opening-value-for-update').val(),
                description_short:$('#description-short-for-update').val(),
                description_long:$('#description-long-for-update').val(),
                wholesale_price:$('#wholesale_price').val(),
                retail_price:$('#retail_price').val(),

            },

            error: function (request, status, error) {
                alert(request.responseText);
            },

            success: function (data) {

                $('#edit-product').hide();
                $('#products-table').DataTable().draw(false);
                $('#top-head').show();
                $('#products-table').parents('div.dataTables_wrapper').first().show();
            }
        });
    });

</script>
