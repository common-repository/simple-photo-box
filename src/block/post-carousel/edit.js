import { Component } from '@wordpress/element';
import { withSelect } from '@wordpress/data';
// import { decodeEntities } from "@wordpress/html-entities";
const { __ } = wp.i18n;

class Edit extends Component {
    render() {
        const { posts } = this.props; 
        console.log( this.props ); 
        return (
            <div>
                {posts && posts.length > 0 ? (
                    <ul>
                        {posts.map(post => (
                            <li key={post.id}>
                                <a
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    href={post.link}
                                >
                                    {post.title.rendered}
                                </a>
                            </li>
                        ))}
                    </ul>
                ) : (
                    <div>
                        {" "}
                        {posts
                            ? __("No Posts Found", "mytheme-blocks")
                            : __("Loading...", "mytheme-blocks")}{" "}
                    </div>
                )}
            </div>
        )
    }
}

export default withSelect(
    ( select, props ) => {
        const { attributes } = props;
        const { numberOfPosts } = attributes;
        const query = { per_page: numberOfPosts };
        return {
            posts: select( 'core' ).getEntityRecords( 'postType', 'post', query )
        }
    }
)(Edit); 