<div id="new-product" class="row col-md-8" style="border-right: solid; overflow: scroll; height: 600px">

    <form class="form-horizontal" role="form" method="POST" action="{{ url('product/productIndex') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        {{ csrf_field() }}

        <table class="table table-striped table-striped table-responsive table-success">
            <tbody>
            <tr>
                <td><label for="name" class="control-label">Name <span style="color: red">*</span></label></td>
                <td colspan="3"><input id="name" type="text" class="form-control" name="name" value=""  autocomplete="off"></td>
            </tr>
            <tr>
                <td><label for="category_id" class="control-label">Category<span style="color: red">*</span></label></td>
                <td>{!! Form::select('category_id', $categories , null , array('id' => 'category_id', 'class' => 'form-control')) !!}</td>
                <td><label for="subcategory_id" class="control-label">Category<span style="color: red">*</span></label></td>
                <td>{!! Form::select('subcategory_id', $subcategories , null , array('id' => 'subcategory_id', 'class' => 'form-control')) !!}</td>
            </tr>

            <tr>
                <td><label for="brand_id" class="control-label">Brand</label></td>
                <td>{!! Form::select('brand_id', $brands , null , array('id' => 'brand_id', 'class' => 'form-control','placeholder' => 'Select Brand...')) !!}</td>
                <td><label for="unit_name" class="control-label">Unit<span style="color: red">*</span></label></td>
                <td>{!! Form::select('unit_name', $units , null , array('id' => 'unit_name', 'class' => 'form-control')) !!}</td>
            </tr>

            <tr>
                <td><label for="second_unit" class="control-label">Second Unit</label></td>
                <td>{!! Form::select('second_unit', $units , null , array('id' => 'second_unit', 'class' => 'form-control','placeholder' => 'Second Unit...')) !!}</td>
                <td><label for="third_unit" class="control-label">Third Unit</label></td>
                <td>{!! Form::select('third_unit', $units , null , array('id' => 'third_unit', 'class' => 'form-control','placeholder' => 'Third Unit...')) !!}</td>
            </tr>

            <tr>
                <td><label for="size_id" class="control-label">Size</label></td>
                <td>{!! Form::select('size_id', $sizes , null , array('id' => 'size_id', 'class' => 'form-control','placeholder' => 'Select Size...')) !!}</td>
                <td><label for="color_id" class="control-label">Color</label></td>
                <td>{!! Form::select('color_id', $colors , null , array('id' => 'color_id', 'class' => 'form-control','placeholder' => 'Select Color...')) !!}</td>
            </tr>

            <tr>
                <td><label for="godown_id" class="control-label">Store</label></td>
                <td>{!! Form::select('godown_id', $stores , null , array('id' => 'godown_id', 'class' => 'form-control','placeholder' => 'Select Store...')) !!}</td>
                <td><label for="rack_id" class="control-label">Rack</label></td>
                <td>{!! Form::select('rack_id', $racks , null , array('id' => 'rack_id', 'class' => 'form-control','placeholder' => 'Select Rack...')) !!}</td>
            </tr>

            <tr>
                <td><label for="sku" class="control-label">SKU</label></td>
                <td><input id="sku" type="text" class="form-control" name="sku" value="" required autocomplete="off"></td>
                <td><label for="autosku" class="control-label" style="text-align: left">SKU Autogenerated</label></td>
                <td><input type="checkbox" name="auto_sku" id="auto_sku" data-toggle="toggle" data-onstyle="primary"></td>
            </tr>

            <tr>
                <td><label for="model_id" class="control-label">Model</label></td>
                <td>{!! Form::select('model_id', $models , null , array('id' => 'model_id', 'class' => 'form-control','placeholder' => 'Select Model...')) !!}</td>
                <td><label for="tax_id" class="control-label">Tax</label></td>
                <td>{!! Form::select('tax_id', $taxes , null , array('id' => 'tax_id', 'class' => 'form-control','placeholder' => 'Select Tax...')) !!}</td>
            </tr>

            <tr>
                <td><label for="reorder_point" class="control-label">Reorder Level</label></td>
                <td><input id="reorder_point" type="text" class="form-control" name="reorder_point" value="{!! 0 !!}" required></td>
                <td><label for="expiry_date" class="control-label" style="text-align: left">Expiry Date</label></td>
                <td><input id="expiry_date" type="text" class="form-control" name="expiry_date" value=""></td>
            </tr>

            <tr>
                <td><label for="description_short" class="control-label">Short Description</label></td>
                <td>{!! Form::textarea('description_short',null,['id'=>'description_short','size' => '20x3','class'=>'field']) !!}</td>
                <td><label for="expiry_date" class="control-label">Long Description</label></td>
                <td>{!! Form::textarea('description_long',null,['id'=>'description_long','size' => '20x3','class'=>'field']) !!}</td>
            </tr>

            <tr>
                <td colspan="3">Upload Product Image</td>
                <td>
                    <div class="imageupload">
                        <div class="file-tab">
                            <label class="btn btn-success btn-file">
                                <span>Upload</span>
                                <!-- The file is stored here. -->
                                <input type="file" name="product-image" id="product-image">
                            </label>
                            <button type="button" class="btn btn-default">Remove</button>
                        </div>
                    </div>

                </td>
            </tr>


            </tbody>
        </table>











        {{--<div class="row form-group{{ $errors->has('relationship_id') ? ' has-error' : '' }}">--}}
        {{--<label for="relationship_id" class="col-md-3 control-label">Suppliers</label>--}}

        {{--<div class="col-md-8">--}}
        {{--{!! Form::select('relationship_id', $suppliers , null , array('id' => 'relationship_id', 'class' => 'form-control','placeholder' => 'Select Suppliers...')) !!}--}}
        {{--@if ($errors->has('relationship_id'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('relationship_id') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}









        {{--******************* NEEDED IF TAX GROUP ACTIVE********************************--}}

        {{--<div class="row form-group{{ $errors->has('taxgrp_code') ? ' has-error' : '' }}">--}}
        {{--<label for="taxgrp_code" class="col-md-3 control-label">Tax Group</label>--}}

        {{--<div class="col-md-8">--}}
        {{--{!! Form::select('taxgrp_code',$taxes, null , array('id' => 'taxgrp_code', 'class' => 'form-control','placeholder' => 'Select Tax Group...')) !!}--}}
        {{--@if ($errors->has('taxgrp_code'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('taxgrp_code') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}

        {{--</div>--}}

        {{--*****************************************************--}}
        {{--<div class="row form-group{{ $errors->has('godown_id') ? ' has-error' : '' }}">--}}
        {{--<label for="godown_id" class="col-md-3 control-label">Godown</label>--}}

        {{--<div class="col-md-8">--}}
        {{--{!! Form::select('godown_id', $godowns , null , array('id' => 'godown_id', 'class' => 'form-control','placeholder' => 'Select Godown...')) !!}--}}
        {{--@if ($errors->has('godown_id'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('godown_id') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}


        {{--<div class="row form-group{{ $errors->has('rack_id') ? ' has-error' : '' }}">--}}

        {{--<label for="rack_id" class="col-md-3 control-label">Rack</label>--}}

        {{--<div class="col-md-8">--}}
        {{--{!! Form::select('rack_id', $racks , null , array('id' => 'rack_id', 'class' => 'form-control','placeholder' => 'Select Rack...')) !!}--}}
        {{--@if ($errors->has('rack_id'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('rack_id') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}

        {{--</div>--}}



        {{--<div class="row form-group{{ $errors->has('unit_price') ? ' has-error' : '' }}">--}}
        {{--<label for="unit_price" class="col-md-3 control-label">Unit Price<span style="color: red">*</span></label>--}}

        {{--<div class="col-md-8">--}}
        {{--<input id="unit_price" type="text" class="form-control" name="unit_price" value="">--}}

        {{--@if ($errors->has('unit_price'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('unit_price') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}



        {{--<div class="row form-group{{ $errors->has('initialPrice') ? ' has-error' : '' }}">--}}
        {{--<label for="initialPrice" class="col-md-3 control-label">Initial Cost Price</label>--}}

        {{--<div class="col-md-8">--}}
        {{--<input id="initialPrice" type="text" class="form-control" name="initialPrice" value="">--}}

        {{--@if ($errors->has('initialPrice'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('initialPrice') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}


        {{--<div class="row form-group{{ $errors->has('wholesalePrice') ? ' has-error' : '' }}">--}}
        {{--<label for="wholesalePrice" class="col-md-3 control-label">Whole Sale Price</label>--}}

        {{--<div class="col-md-8">--}}
        {{--<input id="wholesalePrice" type="text" class="form-control" name="wholesalePrice" value="">--}}

        {{--@if ($errors->has('wholesalePrice'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('wholesalePrice') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}


        {{--<div class="row form-group{{ $errors->has('retailPrice') ? ' has-error' : '' }}">--}}
        {{--<label for="retailPrice" class="col-md-3 control-label">Retail Price</label>--}}

        {{--<div class="col-md-8">--}}
        {{--<input id="retailPrice" type="text" class="form-control" name="retailPrice" value="">--}}

        {{--@if ($errors->has('retailPrice'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('retailPrice') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}



        {{--<div class="row form-group{{ $errors->has('openingQty') ? ' has-error' : '' }}">--}}
        {{--<label for="openingQty" class="col-md-3 control-label">Initial Stock On Hand</label>--}}

        {{--<div class="col-md-8">--}}
        {{--<input id="openingQty" type="text" class="form-control" name="openingQty" value="">--}}

        {{--@if ($errors->has('openingQty'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('openingQty') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--<div class="row form-group{{ $errors->has('openingValue') ? ' has-error' : '' }}">--}}
        {{--<label for="openingValue" class="col-md-3 control-label">Initial Stock Value</label>--}}

        {{--<div class="col-md-8">--}}
        {{--<input id="openingValue" type="text" class="form-control" name="openingValue" value="">--}}

        {{--@if ($errors->has('openingValue'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('openingValue') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}








        <!-- bootstrap-imageupload. -->
{{--        <div class="imageupload card col-md-10 col-md-offset-1">--}}

{{--                <h3 class="card-header pull-left">Upload Image(400X520 JPG 24 COLOR)</h3>--}}
{{--                <div class="btn-group pull-right">--}}
{{--                    <button type="button" class="btn btn-default active">File</button>--}}
{{--                    <button type="button" class="btn btn-default">URL</button>--}}
{{--                </div>--}}
{{--            <div class="file-tab card-body">--}}
{{--                <label class="btn btn-default btn-file">--}}
{{--                    <span>Browse</span>--}}
{{--                    <!-- The file is stored here. -->--}}
{{--                    <input name="imagePath" type="file" name="image-file">--}}
{{--                </label>--}}
{{--                <button type="button" class="btn btn-default">Remove</button>--}}
{{--            </div>--}}

{{--            <div class="url-tab card-body">--}}
{{--                <div class="input-group">--}}
{{--                    <input type="text" class="form-control hasclear" placeholder="Image URL">--}}
{{--                    <div class="input-group-btn">--}}
{{--                        <button type="button" class="btn btn-default">Submit</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <button type="button" class="btn btn-default">Remove</button>--}}
{{--                <!-- The URL is stored here. -->--}}
{{--                <input type="hidden" name="image-url">--}}
{{--            </div>--}}
{{--        </div>--}}




        {{--<div class="row form-group{{ $errors->has('imagePath') ? ' has-error' : '' }}">--}}
        {{--<label for="imagePath" class="col-md-3 control-label">Image</label>--}}

        {{--<div class="col-md-8">--}}
        {{--<input type="file" name="imagePath" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|images/*">--}}

        {{--@if ($errors->has('imagePath'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('imagePath') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}

        <div class="col-md-10 col-md-offset-1">
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
        </div>

    </form>

</div>

<div style="width: 5px"></div>

{{--<div class="col-md-2 col-md-offset-1">--}}
{{--    <article>--}}
{{--        <h1>Help Tips</h1>--}}
{{--        <p>Insert All the related data to the related input fields</p>--}}
{{--    </article>--}}

{{--    <p><strong>Note:</strong> Please upload image size width = 400px and Height=520px</p>--}}
{{--</div>--}}
