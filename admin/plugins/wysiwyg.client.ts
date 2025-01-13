import { EditorState } from 'prosemirror-state';
import { EditorView } from 'prosemirror-view';
import { history, undo, redo } from 'prosemirror-history';
import { Schema } from 'prosemirror-model';
import { defineNuxtPlugin } from '#app';
import { keymap } from "prosemirror-keymap";
import { baseKeymap } from "prosemirror-commands";

// Define your custom schema here
const mySchema = new Schema({
	nodes: {
		doc: { content: 'block+' },
		paragraph: {
			content: 'text*',
			group: 'block',
			toDOM() { return ['p', 0]; },
			parseDOM: [{ tag: 'p' }],
		},
		text: { group: 'inline' },
		heading: {
			attrs: { level: { default: 1 } },
			content: 'text*',
			group: 'block',
			defining: true,
			toDOM(node) { return ['h' + node.attrs.level, 0]; },
			parseDOM: [
				{ tag: 'h1', attrs: { level: 1 } },
				{ tag: 'h2', attrs: { level: 2 } },
				{ tag: 'h3', attrs: { level: 3 } },
			],
		},
	},
	marks: {
		bold: {
			toDOM() { return ['strong', 0]; },
			parseDOM: [{ tag: 'strong' }],
		},
		italic: {
			toDOM() { return ['em', 0]; },
			parseDOM: [{ tag: 'em' }],
		},
		underline: {
			toDOM() { return ['u', 0]; },
			parseDOM: [{ tag: 'u' }],
		},
	},
});

export default defineNuxtPlugin(() => {
	return {
		provide: {
			createWysiwygEditor: (element: HTMLElement) => {
				const state = EditorState.create({
					schema: mySchema, // Ensure schema is correctly passed here
					plugins: [history(), keymap(baseKeymap)],
				});

				const view = new EditorView(element, {
					state,
					dispatchTransaction(transaction) {
						const newState = view.state.apply(transaction);
						view.updateState(newState);
					},
				});

				return {
					view,
					undo: () => undo(view.state, view.dispatch),
					redo: () => redo(view.state, view.dispatch),
				};
			},
		},
	};
});
