/*
* Functionality for the FAQs shortcode view
* 
*/
(function() {
	/*
	* Add event listener to each FAQ header in the page
	*/
	var faqs = document.querySelectorAll( '#accordion article' );

	for(let faq of faqs){
		//adds event listener to header
		//faq.children[0].addEventListener('click',toggleFAQ);
		faq.querySelector('header').addEventListener('click',toggleFAQ);
	}
	console.log('faqs prepared');


	/*
	* Shows/Hides the FAQ answer when the FAQ header is clicked.
	*/
	function toggleFAQ(e){
		e.preventDefault();
		//faq header background turns grey when not collapsed
		this.classList.toggle('grey-background');
	
		//faq content shows when not collapsed
		let faqContent = this.nextElementSibling;
		faqContent.classList.toggle('in');
	
		//a tag reflects changes too
		let aTag = this.querySelector('a');
		aTag.classList.toggle('collapsed');
	
		if(aTag.className.includes('collapsed')){
			aTag.setAttribute('aria-expanded','false');
		} else {
			aTag.setAttribute('aria-expanded', 'true');
		}
	
		//svg icon changes depending on whether faq is collapsed or not
		let dashIcon = this.querySelector('.dashicons');
		dashIcon.classList.toggle('dashicons-arrow-down-alt2');
		dashIcon.classList.toggle('dashicons-arrow-up-alt2');	
	}

})();