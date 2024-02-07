import styles from './Modal.module.css';
function Modal({ children }) {
    return (
        <>
            <div className={styles.backdrop}/>
            <dialog open className={styles.modal}>
                {children}
            </dialog>
        </>
    )
}

export default Modal;