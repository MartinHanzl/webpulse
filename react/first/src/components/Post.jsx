import styles from './Post.module.css';

function Post(props) {
    return (
        <div className={styles.post}>
            <span className={styles.author}>{props.author}</span>
            <br />
            <span>{props.body}</span>
        </div>
    );
}

export default Post;