class Modal extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.innerHTML = `
            <style>
                :host {
                    display: none;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0,0,0,0.5);
                    justify-content: center;
                    align-items: center;
                }
                .modal-content {
                    background-color: #fefefe;
                    margin: auto;
                    padding: 20px;
                    border: 1px solid #888;
                    width: 80%;
                    max-width: 500px;
                    border-radius: 15px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                    animation: fadeIn 0.3s;
                }
                .close {
                    color: #aaa;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: scale(0.9); }
                    to { opacity: 1; transform: scale(1); }
                }
            </style>
            <div class="modal-content">
                <span class="close">&times;</span>
                <slot></slot>
            </div>
        `;

        this.shadowRoot.querySelector('.close').addEventListener('click', () => this.close());
    }

    open() {
        this.style.display = 'flex';
    }

    close() {
        this.style.display = 'none';
    }
}

customElements.define('custom-modal', Modal);
