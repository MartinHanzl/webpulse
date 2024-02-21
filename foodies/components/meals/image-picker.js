'use client';
import styles from './image-picker.module.css';
import {useRef, useState} from "react";
import Image from "next/image";

export default function ImagePicker(props) {
    const [pickedImage, setPickedImage] = useState();
    const imageInput = useRef();

    function handlePickClick() {
        imageInput.current.click();
    }

    function handleImageChange(event) {
        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const fileReader = new FileReader();
        fileReader.onload = () => {
            setPickedImage(fileReader.result);
        };
        fileReader.readAsDataURL(file);
    }

    return <div className={styles.picker}>
        <label htmlFor={props.name}>{props.label}</label>
        <div className={styles.controls}>
            <div className={styles.preview}>
                {!pickedImage && <p>No image!</p>}
                {pickedImage && <Image src={pickedImage} alt="Image selected by the user" fill/>}
            </div>
            <input className={styles.input} type="file" id="image" accept="image/png, image/jpeg" name={props.name}
                   ref={imageInput} onChange={handleImageChange}></input>
            <button className={styles.button} type="button" onClick={handlePickClick}>Upload image</button>
        </div>
    </div>
}