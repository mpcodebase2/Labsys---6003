<?php //= $this->extend('layout/public_dashboard_layout') ?>
<?= $this->extend('layout/landing_layout') ?>


<?= $this->section('main-content') ?>
<?php
function createOption($data, $selected){
    $option = '';
    if(isset($data)){
        foreach ($data as $cdata){
            $_isSelected = (isset($selected) && $cdata->id == $selected)?'selected':'';
            $option.='<option value="'.$cdata->id.'" '.$_isSelected.'>'.$cdata->name.'</option>';
        }
    }
    return $option;
}
function createOptionSimple($ary){
    $option = '';
    if(isset($ary)){
        foreach ($ary as $data){
            $option.='<option value="'.$data['id'].'">'.$data['name'].'</option>';
        }
    }
    return $option;
}
$cityOptions = createOption($city, '');
$districtOptions = createOption($district, '');
$provinceOptions = createOption($province, '');
$countryOptions = createOption($country, '');


?>
    <div class="container-fluid">
        <div class="row widget-grid">
            <div class="col-sm-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h2 style="margin-bottom: 15px;">Create Patient Profile</h2>
                                <form class="theme-form" id="createPatientsForm" method="post">
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                    <div class="row">
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="nic">NIC</label>
                                            <input type="text" class="form-control" name="nic" id="nic" placeholder="Enter NIC" autocomplete="fnn">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="gender">Gender</label>
                                            <select class="form-select" id="gender" name="gender">
                                                <option value="">Select</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="dob">Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" id="dob" placeholder="Enter Date of Birth">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="address_ln1">Address Line 1</label>
                                            <input type="text" class="form-control" name="address_ln1" id="address_ln1" placeholder="Enter Address Line 1" autocomplete="fnn">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="address_ln2">Address Line 2</label>
                                            <input type="text" class="form-control" name="address_ln2" id="address_ln2" placeholder="Enter Address Line 2" autocomplete="fnn">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="city">City</label>
                                            <input class="form-control" id="city" data-autofill-values="colombo|jaffna" name="city" type="text"  placeholder="Wellawatte" maxlength="100" size="50" autocomplete="off">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="district">District</label>
                                            <select class="form-select" id="district" name="district">
                                                <option value="">Select</option>
                                                <?php echo $districtOptions;?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="province">Province</label>
                                            <select class="form-select" id="province" name="province">
                                                <option value="">Select</option>
                                                <?php echo $provinceOptions;?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="country">Country</label>
                                            <select class="form-select" id="country" name="country">
                                                <option value="">Select</option>
                                                <?php echo $countryOptions;?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="telephone">Telephone</label>
                                            <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Enter Telephone" autocomplete="fnn">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="mobile">Mobile</label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile" autocomplete="fnn">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="occupation">Occupation</label>
                                            <input type="text" class="form-control" name="occupation" id="occupation" placeholder="Enter Occupation" autocomplete="fnn">
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="religion">Religion</label>
                                            <select class="form-select" id="religion" name="religion">
                                                <option value="">Select</option>
                                                <option value="Hinduism">Hinduism</option>
                                                <option value="Buddhism">Buddhism</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Catholicism">Catholicism</option>
                                                <option value="Other Christian">Other Christian</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-lg-4 mb-3">
                                            <label class="col-form-label" for="nationality">Nationality</label>
                                            <input class="form-control" id="nationality" data-autofill-values="Sri Lankan" name="nationality" type="text"  placeholder="Sri Lankan" maxlength="100" size="50" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button class="btn btn-primary" id="btnCreate" type="submit">Continue</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?=base_url()?>assets/js/just-validate-plugin-date/dist/just-validate-plugin-date.production.min.js"></script>
    <script src="<?=base_url()?>assets/js/just-validate/dist/just-validate.production.min.js"></script>
    <script src="<?=base_url()?>assets/custom/js/patient-profile.js?dt=<?=time();?>"></script>

    <script>

    <?php
    $cities = array();
    foreach ($city as $c){
        $cities[] = $c->name;
    }
    $nationality = array('Sri Lankan', 'Indian', 'Other');
    ?>

    let city_list = <?php echo json_encode($cities);?>;
    let nationality = <?php echo json_encode($nationality);?>;

    $('#city').autocomplete_init(city_list);
    $('#nationality').autocomplete_init(nationality);


</script>
<?= $this->endSection() ?>