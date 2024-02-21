'use client';
import styles from './image-picker.module.css';
import {useRef} from "react";

export default function ImagePicker(props) {
    const imageInput = useRef('');
    function handlePickClick() {
        imageInput.current.click();
    }

    return <div className={styles.picker}>
        <label htmlFor={props.name}>{props.label}</label>
        <div className={styles.controls}>
            <input className={styles.input} type="file" id="image" accept="image/png, image/jpeg" name={props.name} ref={imageInput}></input>
            <button className={styles.button} type="button" onClick={handlePickClick}>Upload image</button>
        </div>
    </div>
}