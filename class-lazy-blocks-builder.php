<?php

class Lazy_Blocks_Builder {
  private $options = [
    'id' => '',
    'title' => 'title here',
    'icon' => '',
    'keywords' => [],
    'slug' => 'lazyblock/no-slug',
    'description' => '',
    'category' => 'text',
    'category_label' => 'text',
  ];
  private $supports = [
    'customClassName' => true,
    'anchor' => false,
    'align' => [
      0 => 'wide',
      1 => 'full',
    ],
    'html' => false,
    'multiple' => true,
    'inserter' => true,
  ];
  private $ghostkit = [
    'supports' => [
      'spacings' => false,
      'display' => false,
      'scrollReveal' => false,
      'frame' => false,
      'customCSS' => false,
    ],
  ];
  private $controls = [];
  private $code = [
    'output_method' => 'php',
    'editor_html' => '',
    'editor_callback' => '',
    'editor_css' => '',
    'frontend_html' => '',
    'frontend_callback' => '',
    'frontend_css' => '',
    'show_preview' => 'always',
    'single_output' => false,
  ];
  private $condition = [];
  
  private $block = false;
  private $currentControl = null;

  public function __construct( $title, $args = [] ) {
    $this
      ->setOption( 'id', $this->getNewID() )
      ->setOption( 'title', $title )
      ->setOption( 'slug', 'lazyblock/' . sanitize_title( $title ) )
      ;
    return $this;
  }
  private function getNewID() {
    $blocks = lazyblocks()->blocks()->get_blocks();
    $ids = [];
    foreach( $blocks as $block ) {
      $ids[] = intval( $block['id'] );
    }
    arsort( $ids );
    $id = array_shift( $ids );
    ++$id;
    return $id;
  }
  
  public function addText( $name, $args = [] ) {
    $this->addControl( 'text', $name, $args );
    return $this;
  }

  public function addTextarea( $name, $args = [] ) {
    $this->addControl( 'textarea', $name, $args );
    return $this;
  }
  
  public function addNumber( $name, $args = [] ) {
    $this->addControl( 'number', $name, $args );
    return $this;
  }
  
  public function addRange( $name, $args = [] ) {
    $this->addControl( 'range', $name, $args );
    return $this;
  }
  
  public function addUrl( $name, $args = [] ) {
    $this->addControl( 'url', $name, $args );
    return $this;
  }
  
  public function addEmail( $name, $args = [] ) {
    $this->addControl( 'email', $name, $args );
    return $this;
  }
  
  public function addPassword( $name, $args = [] ) {
    $this->addControl( 'password', $name, $args );
    return $this;
  }

  public function addImage( $name, $args = [] ) {
    $this->addControl( 'image', $name, $args );
    return $this;
  }

  public function addGallery( $name, $args = [] ) {
    $this->addControl( 'gallery', $name, $args );
    return $this;
  }
  public function addFile( $name, $args = [] ) {
    $this->addControl( 'file', $name, $args );
    return $this;
  }
  public function addRichTextEditor( $name, $args = [] ) {
    $this->addControl( 'rich_text', $name, $args );
    return $this;
  }
  public function addClassicEditor( $name, $args = [] ) {
    $this->addControl( 'classic_editor', $name, $args );
    return $this;
  }
  public function addCodeEditor( $name, $args = [] ) {
    $this->addControl( 'code_editor', $name, $args );
    return $this;
  }
  public function addInnerBlocks( $name, $args = [] ) {
    $this->addControl( 'inner_blocks', $name, $args );
    return $this;
  }

  public function addRadio( $name, $args = [] ) {
    $this->addControl( 'radio', $name, $args );
    return $this;
  }
  public function addSelect( $name, $args = [] ) {
    $this->addControl( 'select', $name, $args );
    return $this;
  }
  public function addCheckbox( $name, $alongside = '', $args = [] ) {
    $this->addControl( 'checkbox', $name, $args );
    $this->setAlongsideText( $alongside );
    return $this;
  }
  public function addToggle( $name, $alongside = '', $args = [] ) {
    $this->addControl( 'toggle', $name, $args );
    $this->setAlongsideText( $alongside );
    return $this;
  }

  public function startRepeater( $name, $args = [] ) {
    $this->addControl( 'repeater', $name, $args );
    $this->currentRepeater = $this->currentControl;
    return $this;
  }
  public function endRepeater() {
    $this->currentRepeater = null;
    return $this;
  }
  
  public function addDateTime( $name, $args = [] ) {
    $this->addControl( 'date_time', $name, $args );
    return $this;
  }

  public function addColor( $name, $args = [] ) {
    $this->addControl( 'color', $name, $args );
    return $this;
  }
  
  
  public function addControl( $type = 'text', $name = 'text-field', $args = [], $slug = false ) {
    $slug = false === $slug ? uniqid( 'control_' ) : $slug;
    $defaults = [
      'type' => 'text',
      'default' => '',
      'label' => 'Text here',
      'help' => '',
      'child_of' => $this->currentRepeater ?? '',
      'placement' => 'content',  // inspector/content/both
      'width' => '100',
      'hide_if_not_selected' => 'true',
      'save_in_meta' => 'false',
      'save_in_meta_name' => '',
      'required' => 'false',
      'placeholder' => '',
      'characters_limit' => '',      
    ];
    $args = wp_parse_args( $args, $defaults );
    $args['type'] = $type;
    $args['name'] = $args['name'] ?? sanitize_title( $name );
    $args['label'] = $args['label'] ??  ucwords( str_replace( [ '-', '_' ], ' ', $args['name'] ) );

    $this->currentControl = $slug;
    $this->controls[ $slug ] = $args;
    return $this;
  }
  
  public function addControls( $controls ) {
    if ( $controls instanceof Lazy_Blocks_Builder ) {
      $controls = $controls->getControls();
    }
    foreach ( $controls as $slug => $control ) {
      $this->addControl( $control['type'], $control['name'], $control, $slug );
    }
    return $this;
  }
  
  public function getControls() {
    return $this->controls;
  }
  
  public function setProp( $prop, $value, $default = '', $variants = [] ) {
    if ( ! empty( $variants ) ) {
      $value = in_array( $value, $variants, true ) ? $value : $default;      
    }
    $this->controls[ $this->currentControl ][ $prop ] = $value;
    return $this;
  }
  public function setPlacement( $placement ) {
    $this->setProp( 'placement', $placement, 'content', [ 'inspector', 'content', 'both' ] );
    return $this;
  }
  public function setRequired( $required = 'true' ) {
    $this->setProp( 'required', $required, 'false', [ 'true', 'false' ] );
    return $this;
  }
  public function setWidth( $width ) {
    $this->setProp( 'width', $width, '100', [ '100', '75', '50', '25' ] );
    return $this;
  }
  public function setCharacterLimit( $value ) {
    $this->setProp( 'characters_limit', $value, '' );
    return $this;
  }
  public function setDefault( $value ) {
    $this->setProp( 'default', $value, '' );
    return $this;
  }
  public function setPlaceholder( $placeholder ) {
    $this->setProp( 'placeholder', $placeholder, '' );
    return $this;
  }
  public function setHelp( $help ) {
    $this->setProp( 'help', $help, '' );
    return $this;
  }
  public function setMeta( $name ) {
    $this->setProp( 'save_in_meta', 'true' );
    $this->setProp( 'save_in_meta_name', $name );
    return $this;
  }
  public function removeMeta() {
    $this->setProp( 'save_in_meta', 'false' );
    $this->setProp( 'save_in_meta_name', '' );
    return $this;
  }
  
  public function addChoice( $value, $label = false ) {
     $this->controls[ $this->currentControl ][ 'choices' ][] = [
       'label' => $label ? $label : $value,
       'value' => $value,
     ];
    return $this;
  }
  public function addChoices( $choices = [] ) {
    foreach ( $choices as $value => $label ) {
      $this->addChoice( $value, $label );
    }
    return $this;
  }
  
  public function allowNull() {
    $this->setProp( 'allow_null', 'true' );
    return $this;
  }
  public function unallowNull() {
    $this->setProp( 'allow_null', 'false' );
    return $this;
  }
  public function setOutput( $output ) {
    $this->setProp( 'output_format', $output, 'key', [ 'key', 'label', 'array' ] );
    return $this;
  }
  
  public function setChecked() {
    $this->setProp( 'checked', 'true' );
    return $this;
  }
  public function setUnchecked() {
    $this->setProp( 'checked', 'false' );
    return $this;
  }
  public function setAlongsideText( $text ) {
    $this->setProp( 'alongside_text', $text, '' );
    return $this;
  }
  
  public function allowAlpha() {
    $this->setProp( 'alpha', 'true' );
    return $this;
  }
  public function unallowAlpha() {
    $this->setProp( 'alpha', 'false' );
    return $this;
  }
  
  public function setName( $name ) {
    $this->setProp( 'name', $name );
    return $this;
  }

  public function setOption( $key, $value ) {
    $this->options[ $key ] = $value;
    return $this;
  }
  public function setSupport( $key, $value ) {
    $this->supports[ $key ] = $value;
    return $this;
  }
  public function setGhostKit( $key, $value ) {
    $this->ghostkit['supports'][ $key ] = $value;
    return $this;
  }
  public function setCode( $key, $value ) {
    $this->code[ $key ] = $value;
    return $this;
  }

  public function setSingleCallback( $callback ) {
    $this->setCode( 'frontend_callback', $callback );
    $this->setCode( 'editor_callback', '' );
    $this->setCode( 'single_output', true );    
    return $this;
  }
  public function setFrontendCallback( $callback ) {
    $this->setCode( 'frontend_callback', $callback );
    $this->setCode( 'single_output', false );    
    return $this;
  }
  public function setEditorCallback( $callback ) {
    $this->setCode( 'editor_callback', $callback );
    $this->setCode( 'single_output', false );    
    return $this;
  }
  public function setCallbacks( $frontend_callback, $editor_callback ) {
    $this->setCode( 'frontend_callback', $frontend_callback );
    $this->setCode( 'editor_callback', $editor_callback );
    $this->setCode( 'single_output', false );    
    return $this;
  }

  public function addCondition( $value ) {
    $this->condition[] = $value;
    return $this;
  }
  public function setCondition( $key, $value ) {
    $this->condition[ $key ] = $value;
    return $this;
  }
  
  public function build() {
    $block = $this->options;
    $block['supports'] = $this->supports;
    $block['ghostkit'] = $this->ghostkit;
    $block['controls'] = $this->controls;
    $block['code'] = $this->code;
    $block['condition'] = $this->condition;
    $this->block = $block;
    if ( function_exists( 'lazyblocks' ) ) {
      lazyblocks()->add_block( $this->block );
    }
    return $this;
  }
}
