const TableOfContents = {
    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.setupSmoothScroll();
            });
        } else {
            this.setupSmoothScroll();
        }
    },

    setupSmoothScroll() {
        setTimeout(() => {
            document.querySelectorAll('a[href^="#heading-"]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const targetId = link.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        const headerHeight = 80;
                        const elementPosition = targetElement.offsetTop - headerHeight;
                        
                        window.scrollTo({
                            top: elementPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }, 100);
    }
};

window.TableOfContents = TableOfContents;
TableOfContents.init();
export default TableOfContents;
