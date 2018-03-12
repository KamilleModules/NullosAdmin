<?php


namespace Module\NullosAdmin\Helper;


/**
 * Helps creating a configuration for trumbowyg.
 * This configuration looks like the one displayed by method dd (at the end of this class).
 */
class TrumbowygConfigHelper implements ConfigHelperInterface
{

    protected $btns;
    protected $btnsDef;
    protected $templates;
    protected $uploadParameters;
    protected $lang;


    public function __construct()
    {
        $this->prepareByPreset("default");
        $this->templates = [];
        $this->lang = 'en'; // 2 letters, see in www/theme/nullosAdmin/vendors/trumbowyg/dist/langs
    }


    public static function create()
    {
        return new static();
    }


    public function prepareByPreset($preset)
    {
        $this->btns = $this->getButtonsByPreset($preset);
        $this->btnsDef = $this->getButtonDefinitionsByPreset($preset);
        return $this;
    }


    public function getConfig()
    {
        $conf = [
            'lang' => $this->lang,
            'btns' => $this->btns,
            'btnsDef' => $this->btnsDef,
        ];


        // has plugin?
        if ($this->templates || $this->uploadParameters) {
            $plugins = [];
            if ($this->templates) {
                $templates = [];
                foreach ($this->templates as $label => $html) {
                    $templates[] = [
                        "name" => $label,
                        "html" => $html,
                    ];
                }
                $plugins['templates'] = $templates;
            }
            if ($this->uploadParameters) {
                $plugins['upload'] = $this->uploadParameters;
            }
            $conf['plugins'] = $plugins;
        }

        return $conf;
    }


    public static function has($name, array $config)
    {
        foreach ($config['btns'] as $btnArr) {
            if (in_array($name, $btnArr, true)) {
                return true;
            }
        }

        foreach ($config['btnsDef'] as $btnDefArr) {
            if (in_array($name, $btnDefArr['dropdown'], true)) {
                return true;
            }
        }
        return false;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    public function setButtons(array $buttons)
    {
        $this->btns = $buttons;
        return $this;
    }

    public function addButtons(array $buttons)
    {
        foreach ($buttons as $btn) {
            $this->btns[] = $btn;
        }
        return $this;
    }

    public function setButtonDefinitions(array $buttonDefinitions)
    {
        $this->btnsDef = $buttonDefinitions;
        return $this;
    }

    public function setButtonDefinition($name, array $buttonDefinition)
    {
        $this->btnsDef[$name] = $buttonDefinition;
        return $this;
    }

    public function unsetButtonDefinition($name)
    {
        unset($this->btnsDef[$name]);
        return $this;
    }


    /**
     * array of label => html
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
        return $this;
    }

    /**
     * Structure of params is defined here: https://alex-d.github.io/Trumbowyg/documentation/plugins/#plugin-upload
     *
     */
    public function setUploadParameters(array $params)
    {
        $this->uploadParameters = $params;
        return $this;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getButtonDefinitionsByPreset($preset)
    {
        switch ($preset) {
            case "minimal":
                break;
            default:
                return [
                    'media' => [
                        'dropdown' => ['insertImage', 'base64', 'insertAudio', 'noembed', 'upload'],
                        'ico' => 'insertImage',
                    ],
                    'list' => [
                        'dropdown' => ['unorderedList', 'orderedList'],
                        'ico' => 'unorderedList',
                    ],
                    'exponent' => [
                        'dropdown' => ['superscript', 'subscript'],
                        'ico' => 'superscript',
                    ],
                    'align' => [
                        'dropdown' => ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        'ico' => 'justifyLeft',
                    ],
                ];
                break;
        }
        return [];
    }


    protected function getButtonsByPreset($preset)
    {
        switch ($preset) {
            case "minimal":
                return [
                    ['viewHTML'],
                    ['undo', 'redo'], // Only supported in Blink browsers
                    ['formatting'],
                    ['foreColor', 'backColor'],
                    ['strong', 'em', 'del'],
                    ['align'],
                    ['list'],
                    ['exponent'],
                    ['horizontalRule'],
                    ['removeformat'],
                    ['fullscreen'],
                ];
                break;
            default:
                return [
                    ['viewHTML'],
                    ['template'],
                    ['undo', 'redo'], // Only supported in Blink browsers
                    ['formatting'],
                    ['foreColor', 'backColor'],
                    ['strong', 'em', 'del'],
                    ['align'],
                    ['list'],
                    ['exponent'],
                    ['link'],
                    ['media'],
                    ['emoji'],
                    ['horizontalRule'],
                    ['removeformat'],
                    ['fullscreen'],
                ];
                break;
        }
        return [];
    }


    private function dd()
    {
        ?>
        <script>
            jqueryComponent.ready(function () {

                $('#some_textarea').trumbowyg({
                    lang: "fr",
                    autogrow: true,
                    autogrowOnEnter: true,
                    btnsDef: {
                        media: {
                            dropdown: ['insertImage', 'base64', 'insertAudio', 'noembed', 'upload'],
                            ico: 'insertImage'
                        },
                        list: {
                            dropdown: ['unorderedList', 'orderedList'],
                            ico: 'unorderedList'
                        },
                        exponent: {
                            dropdown: ['superscript', 'subscript'],
                            ico: 'superscript'
                        },
                        align: {
                            dropdown: ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                            ico: 'justifyLeft'
                        }
                    },
                    btns: [
                        ['viewHTML'],
                        ['template'],
                        ['undo', 'redo'], // Only supported in Blink browsers
                        ['formatting'],
                        ['foreColor', 'backColor'],
                        ['strong', 'em', 'del'],
                        ['align'],
                        ['list'],
                        ['exponent'],
                        ['link'],
                        ['media'],
                        ['emoji'],
                        ['horizontalRule'],
                        ['removeformat'],
                        ['fullscreen']
                    ],
                    plugins: {
                        templates: [
                            {
                                name: 'Template 1',
                                html: '<p>I am a template!</p>'
                            },
                            {
                                name: 'Template 2',
                                html: '<p>I am a different template!</p>'
                            }
                        ],
                        upload: {}
                    }
                });
            });
        </script>
        <?php
    }
}