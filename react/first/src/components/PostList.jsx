import Post from "./Post.jsx";
import NewPost from "./NewPost.jsx";
import styles from './PostList.module.css';

function PostList() {
    const posts = [
        {
            author: 'Jan',
            body: 'Body 1'
        },
        {
            author: 'Martin',
            body: 'Body 2'
        }
    ];

    const results = [];

    posts.forEach((post) => {
       results.push(
           <li>
               <Post author={post.author} body={post.body} />
           </li>
       )
    });
    return (
        <>
            <NewPost />
        <ul className={styles.ul}>
            {results}
        </ul>
        </>
    );
}

export default PostList;