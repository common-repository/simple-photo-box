/**
 * Block: Code Highlighter Block 
 */
//  Import CSS.
import './editor.scss';
import './style.scss';

import './edit';
import { RichText } from '@wordpress/block-editor';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, RangeControl } from '@wordpress/components';
import Edit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks; 

/**
 * Block Registration 
 */
registerBlockType( 'cgb/block-post-carousel', {
	title: __( 'Post Carousel' ), 
	icon: 'editor-code',
	description: __( 'Display Latest Posts in Sliding Mode' ),
	category: 'guten-post-blocks',
	keywords: [
		__( 'Post Carousel' ),
		__( 'Post' ),
		__( 'Post Slider' ),
	],
	attributes: {
		
	},
	edit: withSelect( ( select ) => {
        return {
            posts: select( 'core' ).getEntityRecords( 'postType', 'post' ),
        };
    } )( ( { posts, className } ) => {
        if ( ! posts ) {
            return 'Loading...';
        }
 
        if ( posts && posts.length === 0 ) {
            return 'No posts';
        }
 
        const post = posts[ 0 ];
 
        return <a className={ className } href={ post.link }>
            { post.title.rendered }
        </a>;
    } ),
	save: ( ) => {
		return null;
	},
} );
