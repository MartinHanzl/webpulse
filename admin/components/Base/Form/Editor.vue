<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { BoldIcon, ItalicIcon, UnderlineIcon, ArrowLongLeftIcon, ArrowLongRightIcon, ListBulletIcon, NumberedListIcon, CodeBracketIcon, CommandLineIcon } from '@heroicons/vue/24/outline';
import { toggleMark } from 'prosemirror-commands';
import { schema } from 'prosemirror-schema-basic';

const editor = ref<HTMLElement | null>(null);
let wysiwyg: any;

onMounted(() => {
  const { $createWysiwygEditor } = useNuxtApp();
  wysiwyg = $createWysiwygEditor(editor.value!);
});

function undo() {
  wysiwyg.undo();
}

function redo() {
  wysiwyg.redo();
}

function format(command: string) {
  const { state, dispatch } = wysiwyg.view;
  let commandFunc;

  switch (command) {
    case "bold":
      commandFunc = toggleMark(wysiwyg.view.state.schema.marks.bold);
      break;
    case "italic":
      commandFunc = toggleMark(wysiwyg.view.state.schema.marks.italic);
      break;
    case "underline":
      commandFunc = toggleMark(wysiwyg.view.state.schema.marks.underline);
      break;
    default:
      return;
  }

  if (commandFunc) {
    commandFunc(state, dispatch);
  }
}
</script>

<template>
	<div class="bg-white shadow text-sm border border-gray-300">
		<div class="bg-gray-100 px-4 py-4 text-grayDark flex gap-x-6">
			<UTooltip text="Zpět">
				<button @click="undo">
					<ArrowLongLeftIcon class="size-5" />
				</button>
			</UTooltip>
			<UTooltip text="Vpřed">
				<button @click="redo">
					<ArrowLongRightIcon class="size-5" />
				</button>
			</UTooltip>
			<div class="h-6 w-px bg-gray-900/20" />
			<UTooltip text="Tučný text">
				<button @click="format('bold')">
					<BoldIcon class="size-5" />
				</button>
			</UTooltip>
			<UTooltip text="Kurzíva">
				<button @click="format('italic')">
					<ItalicIcon class="size-5" />
				</button>
			</UTooltip>
			<UTooltip text="Podtrženo">
				<button @click="format('underline')">
					<UnderlineIcon class="size-5" />
				</button>
			</UTooltip>
			<div class="h-6 w-px bg-gray-900/20" />
			<UTooltip text="Nečíslovaný seznam">
				<button @click="format('bold')">
					<ListBulletIcon class="size-5" />
				</button>
			</UTooltip>
			<UTooltip text="Číslovaný seznam">
				<button @click="format('italic')">
					<NumberedListIcon class="size-5" />
				</button>
			</UTooltip>
			<div class="h-6 w-px bg-gray-900/20" />
      <UTooltip text="Kód">
				<button @click="format('italic')">
					<CodeBracketIcon class="size-5" />
				</button>
			</UTooltip>
      <UTooltip text="Blok kódu">
				<button @click="format('italic')">
					<CommandLineIcon class="size-5" />
				</button>
			</UTooltip>
		</div>
		<div
			ref="editor"
			class="px-4 py-4 bg-white text-grayDark min-h-[256px]"
		/>
	</div>
</template>
