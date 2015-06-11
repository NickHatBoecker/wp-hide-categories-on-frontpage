<?php
class TsCustomizeMultipleSelect extends WP_Customize_Control
{
    /**
     * The type of customize control being rendered.
     */
    public $type = 'ts-multiple-select';

    /**
     * Displays the multiple select on the customize screen.
     */
    public function render_content()
    {
        if (empty($this->choices)) {
            return;
        }
        ?>

        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <select <?php $this->link(); ?> multiple="multiple" size="5">
                <?php
                foreach ($this->choices as $value => $label) {
                    $selected = (in_array($value, $this->value())) ? selected(1, 1, false) : '';
                    echo '<option value="'.esc_attr($value).'"'.$selected.'>'.$label.'</option>';
                }
                ?>
            </select>
        </label>

        <?php
    }
}
