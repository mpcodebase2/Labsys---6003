<?= $this->extend('layout/landing_layout') ?>

<?= $this->section('main-content') ?>

<?php
function createOptionSimple($ary){
    $option = '';
    if(isset($ary)){
        foreach ($ary as $data){
            $option.='<option value="'.$data['id'].'">'.ucfirst($data['name']).'</option>';
        }
    }
    return $option;
}
function createSimple($ary){
    $option = '';
    if(isset($ary)){
        foreach ($ary as $data){
            $option.='<option value="'.$data.'">'.ucfirst($data).'</option>';
        }
    }
    return $option;
}

$patientOptions = createOptionSimple($patients);
$testOptions = createOptionSimple($tests);
$doctorOptions = createOptionSimple($doctors);
$statusOptions = createSimple($status);
?>
<style>
    @media (min-width:1025px) {
        #createAppointmentsForm {
            width: 60%;
            margin: auto;
        }
    }
</style>
<div class="container-fluid">
    <div class="row widget-grid">
        <div class="col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Make an Appointment</h2>
                        </div>
                        <div class="card-body">
                            <form class="theme-form" id="createAppointmentsForm" method="post">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                <div class="row">
                                    <div class="form-group col-12 mb-3">
                                        <label class="col-form-label pt-0" for="test_id">Test<i>*</i></label>
                                        <select class="form-select" id="test_id" name="test_id">
                                            <option value="">Select</option>
                                            <?php echo $testOptions;?>
                                        </select>
                                    </div>
                                    <div class="form-group field-radio-group  col-12 mb-3">
                                        <label for="expected_date" class="col-form-label pt-0">Note</label>
                                        <textarea class="form-text" id="note" name="note" style="width: 100%;"></textarea>
                                    </div>
                                    <div class="form-group col-12 mb-3">
                                        <label class="col-form-label" for="amount">Amount<i>*</i></label>
                                        <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="amount" id="amount" placeholder="0.00" autocomplete="fnn" readonly/>
                                    </div>
                                    <div class="form-group col-12 mb-3">
                                        <label class="col-form-label" for="paid">Amount to be pay<i>*</i></label>
                                        <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="paid" id="paid" placeholder="0.00" autocomplete="fnn"/>
                                    </div>
                                    <div class="form-group col-12 mb-3">
                                        <label class="col-form-label" for="due">Due will be<i>*</i></label>
                                        <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="due" id="due" placeholder="0.00" autocomplete="fnn" readonly/>
                                    </div>
                                    <div class="form-group field-radio-group  col-12 mb-3">
                                        <label for="paid_via" class="radio-group-label">Paid via</label>
                                        <select class="form-select" id="paid_via" name="paid_via">
                                            <option value="">Select</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Card">Card</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Online">Online</option>
                                            <option value="Card">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn btn-primary" id="btnAddAppointment" type="submit">NEXT</button>
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
    <script src="<?=base_url()?>assets/custom/js/appointments-p.js?dt=<?=time();?>"></script>


<?= $this->endSection() ?>