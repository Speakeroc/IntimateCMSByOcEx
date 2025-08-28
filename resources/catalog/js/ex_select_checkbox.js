document.addEventListener('DOMContentLoaded', () => {
    function updateCheckboxCount(containerId) {
        const container = document.getElementById(containerId);
        const checkedCheckboxes = container.querySelectorAll('.form-check-input:checked');
        const titleSpan = container.querySelector('.ex_select_checkbox_title span');
        if (checkedCheckboxes.length > 0) {
            titleSpan.textContent = `${checkedCheckboxes.length}`;
        } else {
            titleSpan.textContent = doesnt_matter;
        }
    }
    const checkboxContainers = document.querySelectorAll('.ex_select_checkbox_block');
    checkboxContainers.forEach(container => {
        container.addEventListener('change', (event) => {
            if (event.target.classList.contains('form-check-input')) {
                updateCheckboxCount(container.id);
            }
        });
        updateCheckboxCount(container.id);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const titles = document.querySelectorAll('.ex_select_checkbox_title');
    titles.forEach(title => {
        title.addEventListener('click', (event) => {
            event.stopPropagation();
            const parentBlock = title.closest('.ex_select_checkbox_block');
            document.querySelectorAll('.ex_select_checkbox_block').forEach(block => {
                if (block !== parentBlock) {
                    block.classList.remove('active');
                }
            });
            parentBlock.classList.toggle('active');
        });
    });
    document.addEventListener('click', (event) => {
        document.querySelectorAll('.ex_select_checkbox_block').forEach(block => {
            if (!block.contains(event.target)) {
                block.classList.remove('active');
            }
        });
    });
});
