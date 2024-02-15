import styles from './meals-grid.module.css';
import MealItem from "@/components/meals/meal-item";
export default function MealsGrid(props) {
    return (
        <ul className={styles.meals}>
            {props.meals.map((meal) => {
                <MealItem {...meal} />
            })}
        </ul>
    )
}