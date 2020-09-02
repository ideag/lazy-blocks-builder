# Lazy Blocks Builder

Create configuration arrays for [Lazy Blocks](https://lazyblocks.com/) WordPress plugin using the builder pattern and a fluent API.

Quickly create, register, and reuse custom Lazy Blocks and keep them in your source code repository.

### Simple Example
```php
$block = new Lazy_Blocks_Builder( 'Custom Block' );
$block
  ->setOption( 'description', 'A sample block for WordSesh' );
$block
  ->addText( 'heading' )
    ->setDefault( 'Big heading here' )
    ->setRequired()
  ->addRichTextEditor( 'copy' )
  	->setDefault( 'Some lorem ipsum here as a placeholder')
;
$block->setSingleCallback( function( $data ) { /* ... */ } );
$block->build();
```

### Reusable Fields

You can also create a set of fields that can be reused in many blocks.

```php
$background = new Lazy_Blocks_Builder( 'Background Options' );
$background
  ->addImage( 'background_image' )
  ->addColorPicker( 'background_color' )
;

$block_a = new Lazy_Blocks_Builder( 'Block A' );
$block_a
  ->addText( 'heading' )
  ->addRichTextEditor( 'copy' )
  ->addControls( $background )
$block_a->setSingleCallback( function( $data ) { /* ... */ } );
$block_a->build();

$block_b = new Lazy_Blocks_Builder( 'Block B' );
$block_b
  ->addText( 'heading' )
  ->addText( 'sub-heading' )
  ->addRichTextEditor( 'copy' )
  ->addControls( $background )
$block_b->setSingleCallback( function( $data ) { /* ... */ } );
$block_b->build();
```

## Requirements
PHP 7.0 and later are supported.
