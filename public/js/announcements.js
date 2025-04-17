// BB kód beszúrása
function insertBBCode(tag) {
    const contentField = document.getElementById('content') || document.getElementById('edit_content');
    if (!contentField) return;
    
    const startPos = contentField.selectionStart;
    const endPos = contentField.selectionEnd;
    const selectedText = contentField.value.substring(startPos, endPos);
    
    if (selectedText) {
        const openTag = tag.split('][')[0] + ']';
        const closeTag = '[' + tag.split('][')[1];
        const newText = openTag + selectedText + closeTag;
        contentField.value = contentField.value.substring(0, startPos) + newText + contentField.value.substring(endPos);
    } else {
        if (tag === '[url=][/url]') {
            const url = prompt('Add meg az URL-t:');
            if (url) {
                contentField.value = contentField.value.substring(0, startPos) + 
                    `[url=${url}]Link szövege[/url]` + 
                    contentField.value.substring(endPos);
            }
        } else {
            const tagParts = tag.split('][');
            contentField.value = contentField.value.substring(0, startPos) + 
                tagParts[0] + ']' + 'szöveg' + '[' + tagParts[1] + 
                contentField.value.substring(endPos);
        }
    }
    contentField.focus();
}

// Emoji beszúrása
function insertEmoji(emoji) {
    const contentField = document.getElementById('content') || document.getElementById('edit_content');
    if (!contentField) return;
    
    const startPos = contentField.selectionStart;
    contentField.value = contentField.value.substring(0, startPos) + emoji + contentField.value.substring(startPos);
    contentField.focus();
}

// Közlemény modal megnyitása
function showAnnouncement(id) {
    fetch(`/announcements/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modalOverlay = document.createElement('div');
            modalOverlay.className = 'announcements-modal fixed inset-0 flex items-center justify-center p-4';
            modalOverlay.onclick = function(e) {
                if (e.target === modalOverlay) {
                    closeModal();
                }
            };
            modalOverlay.innerHTML = data.html;
            document.body.appendChild(modalOverlay);

            // ESC gombra bezárás
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        }
    });
}

// Új közlemény modal megnyitása
function showCreateAnnouncementModal() {
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'announcements-modal fixed inset-0 flex items-center justify-center p-4';
    modalOverlay.onclick = function(e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    };
    modalOverlay.innerHTML = `
        <div class="announcements-modal-container max-w-2xl w-full">
            <div class="announcements-modal-header">
                <h2 class="announcements-title">Új közlemény létrehozása</h2>
                <button onclick="closeModal()" class="announcements-button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="announcements-modal-body">
                <form id="createAnnouncementForm" class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-300">Cím</label>
                        <input type="text" id="title" name="title" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-300">Tartalom</label>
                        <div class="mt-1 mb-4 flex flex-wrap gap-2">
                            ${['[b][/b]', '[i][/i]', '[u][/u]', '[url=][/url]', '[img][/img]', '[quote][/quote]'].map(code => 
                                `<button type="button" onclick="insertBBCode('${code}')" class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-3 py-1.5 rounded-md text-sm font-medium transition-colors">
                                    ${code.replace(/[\[\]\/]/g, '').split('=')[0].toUpperCase()}
                                </button>`
                            ).join('')}
                        </div>
                        <div class="mb-4 grid grid-cols-8 gap-2">
                            ${['😀', '😂', '😍', '😎', '👍', '🎉', '🔥', '💯', '🥳', '😡', '😢', '🤔', '❤️', '👏', '🙌', '😅', '🎁', '💡', '📢'].map(emoji =>
                                `<button type="button" onclick="insertEmoji('${emoji}')" class="bg-gray-700 hover:bg-gray-600 text-2xl p-2 rounded-md transition-colors">
                                    ${emoji}
                                </button>`
                            ).join('')}
                        </div>
                        <textarea id="content" name="content" rows="6" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                </form>
            </div>
            <div class="announcements-modal-footer">
                <button onclick="closeModal()" class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-4 py-2 rounded-lg transition-colors">Mégse</button>
                <button onclick="submitAnnouncement()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">Létrehozás</button>
            </div>
        </div>
    `;
    document.body.appendChild(modalOverlay);
}

// Közlemény szerkesztése modal megnyitása
function editAnnouncement(id, title, content) {
    fetch(`/announcements/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modalOverlay = document.createElement('div');
            modalOverlay.className = 'announcements-modal fixed inset-0 flex items-center justify-center p-4';
            modalOverlay.innerHTML = data.html;
            document.body.appendChild(modalOverlay);

            // Modal bezárása kattintásra a háttéren
            modalOverlay.addEventListener('click', (e) => {
                if (e.target === modalOverlay) {
                    closeModal();
                }
            });
        }
    });
}

// Közlemény frissítése
function updateAnnouncement() {
    const form = document.getElementById('editAnnouncementForm');
    const id = form.querySelector('#announcement_id').value;
    const title = form.querySelector('#edit_title').value;
    const content = form.querySelector('#edit_content').value;

    fetch(`/announcements/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ title, content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadAnnouncements();
        }
    });
}

// Modal bezárása
function closeModal() {
    const modal = document.querySelector('.announcements-modal');
    if (modal) {
        modal.remove();
    }
}

// Közlemény létrehozása
function submitAnnouncement() {
    const form = document.getElementById('createAnnouncementForm');
    const formData = new FormData();
    formData.append('title', form.querySelector('#title').value);
    formData.append('content', form.querySelector('#content').value);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    fetch('/announcements', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadAnnouncements();
            showNotification('Közlemény sikeresen létrehozva!', 'success');
        }
    });
}

// Közlemények betöltése
function loadAnnouncements(url = null) {
    const currentUrl = url || '/announcements';
    fetch(currentUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.announcements-list').innerHTML = data.html;
        }
    });
}

// Közlemény törlése
function deleteAnnouncement(id) {
    if (confirm('Biztosan törölni szeretnéd ezt a közleményt?')) {
        fetch(`/announcements/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                loadAnnouncements();
                showNotification('Közlemény sikeresen törölve!', 'success');
            }
        });
    }
}

// Lapozás
function loadPage(url) {
    loadAnnouncements(url);
}
