<?php

function fg_form_generator_render($atts) {
    $inputs = $atts['content'] ?? '[]';
    $actionForm = $atts['actionForm'];
    $inputElements = [];
    $form;

    ob_start();
        foreach( $inputs as $input ) {
            $builder = new InputBuilder();
            $director = new Director($builder);
            $inputElements[] = $director->buildInput($input);
        }
        $form = '<form id="fgCustomForm" action="' . $actionForm . '" method="POST">' . implode('<br>', $inputElements)  . '</form>';
    ?>
    <?php echo $form; ?>
    <?php
    $output = ob_get_contents();

    ob_end_clean();
    return $output;
}

class InputBuilder {
    private $input_name;
    private $input_type;
    private $input_className;
    private $input_required = true;

    public function setName($name) {
        $this->input_name = $name;
        return $this;
    }

    public function setType($type) {
        $this->input_type = $type;
        return $this;
    }

    public function setClassName($className) {
        $this->input_className = $className;
        return $this;
    }

    public function setRequired($required) {
        $this->input_required = $required;
        return $this;
    }

    public function build() {
        $required = $this->input_required ? ' required' : '';
        $input = '<label for="' . $this->input_name . '">' . $this->input_name . '</label><br>';

        if ($this->input_type == 'textarea') {
            return $input . '<textarea name="' . strtolower($this->input_name)  . '" class="' . $this->input_className . '"' . $required . '></textarea>';
        } else {
            return $input . '<input type="' . $this->input_type . '" name="' . strtolower($this->input_name) . '" class="' . $this->input_className . '"' . $required . '>';
        }
    }
}

class Director {
    private $builder;

    public function __construct(InputBuilder $builder) {
        $this->builder = $builder;
    }

    public function buildInput($input) {
        $output;
        $output = $this->builder
            ->setName($input['name'])
            ->setType($input['type'])
            ->setClassName($input['className'])
            ->setRequired($input['required'])
            ->build();

        return $output;
    }
}
?>