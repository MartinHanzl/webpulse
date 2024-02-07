import {useState} from "react";

import Post from "./Post.jsx";
import NewPost from "./NewPost.jsx";
import styles from './PostList.module.css';
import Modal from "./Modal.jsx";

function PostList() {
    const [body, setBody] = useState('');
    const [author, setAuthor] = useState('');

    function bodyChangeHandler(event) {
        setBody(event.target.value);
    }

    function authorChangeHandler(event) {
        setAuthor(event.target.value);
    }

    return (
        <>
            <Modal>
                <NewPost
                    onBodyChange={bodyChangeHandler}
                    onAuthorChange={authorChangeHandler}
                />
            </Modal>
            <ul className={styles.ul}>
                <Post author={author} body={body}/>
                <Post author="Petr" body="Nevím"/>
            </ul>
        </>
    );
}

export default PostList;