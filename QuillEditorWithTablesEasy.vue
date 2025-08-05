<template>
  <div class="content-section">
    <label class="section-label">Main Content</label>
    <QuillEditor
      v-model:content="mainContent"
      contentType="html"
      :placeholder="placeholder || 'Enter main content...'"
      :readOnly="disabled"
      :toolbar="toolbarOptions"
      :modules="modules"
      class="quill-editor-wrapper main-editor"
      @ready="onEditorReady"
    />
  </div>
</template>

<script>
import { ref, watch, onMounted, nextTick } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

export default {
  name: 'QuillEditorWithTablesEasy',
  components: {
    QuillEditor
  },
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Enter main content...'
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const mainContent = ref(props.modelValue);
    const quillInstance = ref(null);

    // Enhanced toolbar with table functions
    const toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }, { background: [] }],
      [{ font: [] }],
      [{ size: ['small', false, 'large', 'huge'] }],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ script: 'sub' }, { script: 'super' }],
      [{ indent: '-1' }, { indent: '+1' }],
      [{ align: ['', 'center', 'right', 'justify'] }],
      ['code-block'],
      ['link', 'image'],
      // Custom table buttons
      ['insertTable', 'addTableRow', 'addTableCol', 'removeTableRow', 'removeTableCol'],
      ['clean'] // remove formatting button
    ];

    // Quill modules configuration
    const modules = {
      toolbar: {
        container: toolbarOptions,
        handlers: {
          'insertTable': function() {
            insertTable(this.quill);
          },
          'addTableRow': function() {
            addTableRow(this.quill);
          },
          'addTableCol': function() {
            addTableColumn(this.quill);
          },
          'removeTableRow': function() {
            removeTableRow(this.quill);
          },
          'removeTableCol': function() {
            removeTableColumn(this.quill);
          }
        }
      }
    };

    // Table manipulation functions
    const insertTable = (quill) => {
      const range = quill.getSelection();
      if (range) {
        const tableHTML = `
          <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
            <tbody>
              <tr>
                <td style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9;"><strong>Header 1</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9;"><strong>Header 2</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9;"><strong>Header 3</strong></td>
              </tr>
              <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">Cell 1</td>
                <td style="border: 1px solid #ddd; padding: 8px;">Cell 2</td>
                <td style="border: 1px solid #ddd; padding: 8px;">Cell 3</td>
              </tr>
              <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">Cell 4</td>
                <td style="border: 1px solid #ddd; padding: 8px;">Cell 5</td>
                <td style="border: 1px solid #ddd; padding: 8px;">Cell 6</td>
              </tr>
            </tbody>
          </table>
          <p><br></p>
        `;
        quill.clipboard.dangerouslyPasteHTML(range.index, tableHTML);
      }
    };

    const addTableRow = (quill) => {
      const selection = quill.getSelection();
      if (selection) {
        const [leaf] = quill.getLeaf(selection.index);
        const table = findParentTable(leaf.domNode);
        if (table) {
          const tbody = table.querySelector('tbody');
          const firstRow = tbody.querySelector('tr');
          if (firstRow) {
            const cellCount = firstRow.children.length;
            const newRow = document.createElement('tr');
            for (let i = 0; i < cellCount; i++) {
              const cell = document.createElement('td');
              cell.style.cssText = 'border: 1px solid #ddd; padding: 8px;';
              cell.innerHTML = `New Cell ${i + 1}`;
              newRow.appendChild(cell);
            }
            tbody.appendChild(newRow);
            quill.update();
          }
        }
      }
    };

    const addTableColumn = (quill) => {
      const selection = quill.getSelection();
      if (selection) {
        const [leaf] = quill.getLeaf(selection.index);
        const table = findParentTable(leaf.domNode);
        if (table) {
          const rows = table.querySelectorAll('tr');
          rows.forEach((row, index) => {
            const cell = document.createElement('td');
            cell.style.cssText = 'border: 1px solid #ddd; padding: 8px;';
            if (index === 0) {
              cell.style.backgroundColor = '#f9f9f9';
              cell.innerHTML = '<strong>New Header</strong>';
            } else {
              cell.innerHTML = 'New Cell';
            }
            row.appendChild(cell);
          });
          quill.update();
        }
      }
    };

    const removeTableRow = (quill) => {
      const selection = quill.getSelection();
      if (selection) {
        const [leaf] = quill.getLeaf(selection.index);
        const row = findParentElement(leaf.domNode, 'TR');
        if (row) {
          const table = findParentTable(row);
          const tbody = table.querySelector('tbody');
          if (tbody.children.length > 1) {
            row.remove();
            quill.update();
          }
        }
      }
    };

    const removeTableColumn = (quill) => {
      const selection = quill.getSelection();
      if (selection) {
        const [leaf] = quill.getLeaf(selection.index);
        const cell = findParentElement(leaf.domNode, 'TD');
        if (cell) {
          const table = findParentTable(cell);
          const columnIndex = Array.from(cell.parentNode.children).indexOf(cell);
          const rows = table.querySelectorAll('tr');
          
          // Don't remove if it's the last column
          if (rows[0].children.length > 1) {
            rows.forEach(row => {
              if (row.children[columnIndex]) {
                row.children[columnIndex].remove();
              }
            });
            quill.update();
          }
        }
      }
    };

    // Helper functions
    const findParentElement = (element, tagName) => {
      while (element && element.tagName !== tagName) {
        element = element.parentElement;
      }
      return element;
    };

    const findParentTable = (element) => {
      while (element && element.tagName !== 'TABLE') {
        element = element.parentElement;
      }
      return element;
    };

    // Handle editor ready event
    const onEditorReady = (quill) => {
      quillInstance.value = quill;
    };

    // Watch for changes and emit to parent
    watch(mainContent, (newValue) => {
      emit('update:modelValue', newValue);
    });

    // Watch for prop changes
    watch(() => props.modelValue, (newValue) => {
      if (newValue !== mainContent.value) {
        mainContent.value = newValue;
      }
    });

    return {
      mainContent,
      toolbarOptions,
      modules,
      onEditorReady
    };
  }
};
</script>

<style scoped>
.content-section {
  margin-bottom: 1.5rem;
}

.section-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
}

.quill-editor-wrapper {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  overflow: hidden;
}

.main-editor {
  min-height: 200px;
}

/* Custom toolbar button styles */
:deep(.ql-toolbar .ql-insertTable) {
  width: auto;
}

:deep(.ql-toolbar .ql-insertTable::after) {
  content: "📋 Table";
  font-size: 11px;
}

:deep(.ql-toolbar .ql-addTableRow::after) {
  content: "➕ Row";
  font-size: 10px;
}

:deep(.ql-toolbar .ql-addTableCol::after) {
  content: "➕ Col";
  font-size: 10px;
}

:deep(.ql-toolbar .ql-removeTableRow::after) {
  content: "➖ Row";
  font-size: 10px;
}

:deep(.ql-toolbar .ql-removeTableCol::after) {
  content: "➖ Col";
  font-size: 10px;
}

/* Table styles in editor */
:deep(.ql-editor table) {
  border-collapse: collapse;
  width: 100%;
  margin: 10px 0;
}

:deep(.ql-editor table td) {
  border: 1px solid #ddd;
  padding: 8px;
  vertical-align: top;
  min-width: 50px;
}

:deep(.ql-editor table td:hover) {
  background-color: #f0f9ff;
}

/* Responsive design */
@media (max-width: 768px) {
  :deep(.ql-toolbar) {
    flex-wrap: wrap;
  }
  
  :deep(.ql-editor table) {
    font-size: 14px;
    overflow-x: auto;
    display: block;
    white-space: nowrap;
  }
  
  :deep(.ql-editor table td) {
    padding: 6px;
    min-width: 80px;
  }
  
  :deep(.ql-toolbar button) {
    padding: 1px 2px;
    margin: 0.5px;
  }
}
</style>