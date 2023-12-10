<div class="acc-body mahram-detail mt-3 ml-1" id="mahram-detail">
    <h5>
        <i class="fas fa-file-alt"></i>
        <span class="m-2">{{ Lang::get('core.mahram_detail') }}</span>
    </h5>

    <h6 class="mt-4 legend1 traveller1" id="mahram1"></h6>
    <hr>

    <div class="row">
        <div class="form-group col-md-12">
            <div>
                <input type='number' name='adult_number' id="adult_count" min="1" placeholder="{{ Lang::get('core.adult_number') }} *" required class='form-control required' />
            </div>
        </div>
        <div class="form-group col-md-12">
            <div>
                <input type='number' name='child_number' id="child_count" required class='form-control required' placeholder="{{ Lang::get('core.child_number') }}" />
            </div>
        </div>
        <div class="form-group col-md-12">
            <div>
                <input type='number' name='infant_number' id="infant_count" required class='form-control required' placeholder="{{ Lang::get('core.infant_number') }}" />
            </div>
        </div>

    </div>
</div>
<div class="float-right">
    <button type="button" onclick="backMahram();" class="btn btn-default">{{ Lang::get('core.btn_back') }}</button>
    <button type="button" onclick="continueMahram();" class="btn btn-primary">{{ Lang::get('core.btn_continue') }}</button>
</div>