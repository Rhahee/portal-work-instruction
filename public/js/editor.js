import { Editor } from 'https://esm.sh/@tiptap/core@2.11.5';
import StarterKit from 'https://esm.sh/@tiptap/starter-kit@2.11.5';
import Underline from 'https://esm.sh/@tiptap/extension-underline@2.11.5';
import Link from 'https://esm.sh/@tiptap/extension-link@2.11.5';
import Image from 'https://esm.sh/@tiptap/extension-image@2.11.5';
import Highlight from 'https://esm.sh/@tiptap/extension-highlight@2.11.5';

const mount = document.querySelector('#editor');
if (mount) {
  let content = { type: 'doc', content: [] };
  try { content = JSON.parse(mount.dataset.content); } catch (_) {}
  if (mount.dataset.html) {
    try { content = JSON.parse(mount.dataset.html); } catch (_) {}
  }
  const editor = new Editor({ element: mount, content, extensions: [StarterKit, Underline, Highlight, Link.configure({ openOnClick: false }), Image] });
  const run = (command) => {
    const chain = editor.chain().focus();
    if (command === 'undo') chain.undo().run();
    else if (command === 'redo') chain.redo().run();
    else if (command === 'bold') chain.toggleBold().run();
    else if (command === 'italic') chain.toggleItalic().run();
    else if (command === 'underline') chain.toggleUnderline().run();
    else if (command === 'strike') chain.toggleStrike().run();
    else if (command === 'inlineCode') chain.toggleCode().run();
    else if (command === 'heading') chain.toggleHeading({ level: 2 }).run();
    else if (command === 'bulletList') chain.toggleBulletList().run();
    else if (command === 'orderedList') chain.toggleOrderedList().run();
    else if (command === 'link') {
      if (editor.state.selection.empty) return window.alert('Pilih teks yang ingin dijadikan hyperlink terlebih dahulu.');
      const href = window.prompt('Masukkan URL hyperlink'); if (href) chain.setLink({ href }).run();
    }
    else if (command === 'image') document.querySelector('#image-upload').click();
    else if (command === 'toggleHighlight') chain.toggleHighlight().run();
    else if (command === 'indent') {
      if (editor.can().sinkListItem('listItem')) chain.sinkListItem('listItem').run();
      else window.alert('Indent hanya berlaku saat kursor berada pada item list.');
    }
    else if (command === 'outdent') {
      if (editor.can().liftListItem('listItem')) chain.liftListItem('listItem').run();
      else window.alert('Outdent hanya berlaku saat kursor berada pada item list bertingkat.');
    }
    else if (command === 'bash') chain.toggleCodeBlock().run();
    else if (command === 'divider') chain.setHorizontalRule().run();
  };
  document.querySelector('#editor-toolbar').innerHTML = `
    <button type="button" data-command="undo" title="Undo" aria-label="Undo">↶</button><button type="button" data-command="redo" title="Redo" aria-label="Redo">↷</button><span></span>
    <button type="button" data-command="bold" title="Bold (Ctrl+B)" aria-label="Bold"><b>B</b></button><button type="button" data-command="italic" title="Italic (Ctrl+I)" aria-label="Italic"><i>I</i></button><button type="button" data-command="underline" title="Underline (Ctrl+U)" aria-label="Underline"><u>U</u></button><button type="button" data-command="strike" title="Strikethrough" aria-label="Strikethrough"><s>S</s></button><button type="button" data-command="inlineCode" title="Kode inline (Ctrl+Shift+E)" aria-label="Kode inline">&lt;/&gt;</button><span></span>
    <button type="button" data-command="heading" title="Sub-Judul (Ctrl+Alt+2)" aria-label="Sub-Judul">Sub-Judul</button><button type="button" data-command="bulletList" title="Bullet list" aria-label="Bullet list">• List</button><button type="button" data-command="orderedList" title="Numbered list" aria-label="Numbered list">1. List</button><button type="button" data-command="indent" title="Indent list" aria-label="Indent list">⇥</button><button type="button" data-command="outdent" title="Outdent list" aria-label="Outdent list">⇤</button><span></span>
    <button type="button" data-command="link" title="Buat hyperlink dari teks terpilih (Ctrl+K)" aria-label="Buat hyperlink">🔗</button><button type="button" data-command="image" title="Sisipkan Gambar" aria-label="Sisipkan Gambar">Sisipkan Gambar</button><button type="button" data-command="divider" title="Sisipkan separator" aria-label="Sisipkan separator">—</button><button type="button" data-command="bash" title="Blok Bash (Ctrl+Alt+B)" aria-label="Blok Bash">Bash</button><span></span>
    <button type="button" data-command="toggleHighlight" title="Highlight" aria-label="Highlight">▰</button>`;
  document.querySelectorAll('[data-command]').forEach((button) => button.addEventListener('click', () => run(button.dataset.command)));
  mount.addEventListener('keydown', (event) => {
    if (event.ctrlKey && event.altKey && event.key === '2') { event.preventDefault(); run('heading'); }
    if (event.ctrlKey && event.altKey && event.key.toLowerCase() === 'b') { event.preventDefault(); run('bash'); }
    if (event.ctrlKey && !event.altKey && event.key.toLowerCase() === 'k') { event.preventDefault(); run('link'); }
    if (event.ctrlKey && event.shiftKey && !event.altKey && event.key.toLowerCase() === 'e') { event.preventDefault(); run('inlineCode'); }
  });
  const file = document.createElement('input'); file.id = 'image-upload'; file.type = 'file'; file.accept = 'image/png,image/jpeg,image/webp'; file.hidden = true; document.body.append(file);
  file.addEventListener('change', async () => { if (!file.files[0]) return; const body = new FormData(); body.append('image', file.files[0]); const response = await fetch('/manage/uploads/images', { method: 'POST', body, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' } }); if (!response.ok) return alert('Gambar tidak dapat diunggah. Pastikan format dan ukuran sesuai.'); editor.chain().focus().setImage({ src: (await response.json()).url }).run(); file.value = ''; });
  document.querySelector('#wi-form').addEventListener('submit', () => { document.querySelector('#content_json').value = JSON.stringify(editor.getJSON()); document.querySelector('#content_html').value = editor.getHTML(); });
}
