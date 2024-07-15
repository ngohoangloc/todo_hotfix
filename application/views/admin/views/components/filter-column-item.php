<div class="group-filter-column-item row mb-2">
    <div class="col-5">
        <label class="form-label">Cột</label>
        <div class="row">
            <div class="col-4">
                <select name="select-filter-logic" id="" class="form-select">
                    <option value="AND">And</option>
                    <option value="OR">Or</option>
                </select>
            </div>
            <div class="col-8">
                <select name="select-filter-fields" class="form-select">
                    <!-- Load fields -->
                    <option value="" disabled selected> --- Chọn cột --- </option>
                    <?php foreach ($this->Items_model->get_fields($project->id) as $field) : ?>
                        <option value="<?= $field->key; ?>" data-field-type="<?= $field->type_html; ?>"><?= $field->title ?></option>
                    <?php endforeach; ?>
                    <!-- End Load fields -->
                </select>
            </div>
        </div>
    </div>
    <div class="col-2">
        <label class="form-label">Điều kiện</label>
        <select name="select-filter-condition" id="" class="form-select">
            <option value="1"> <?= "=" ?></option>
            <option value="2"> <?= ">=" ?> </option>
            <option value="3"> <?= "<=" ?> </option>
            <option value="4"> <?= "!=" ?> </option>
            <option value="5"> <?= ">" ?></option>
            <option value="6"> <?= "<" ?></option>
        </select>
    </div>
    <div class="col-5">
        <label class="form-label">Giá trị</label>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="input-filter-value input-text-filter-values form-control" hidden>

            <select disabled name="select-filter-values" class="input-filter-value form-select">
                <option value="" disabled selected> --- Chọn giá trị --- </option>
            </select>

            <span class="btn-remove-filter-column" style="cursor: pointer;">
                <i class="fa fa-times"></i>
            </span>
        </div>
        <span>
        </span>
    </div>
</div>