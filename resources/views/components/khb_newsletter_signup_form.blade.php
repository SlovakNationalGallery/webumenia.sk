<form class="khb-newsletter-signup-form js-newsletter-signup-form" method="post" action="{{ route('newsletter.signup') }}">
    <div class="d-sm-none">
    	<label for="newsletterEmail" class="col-form-label border-0">Newsletter</label>
    </div>
    <div class="form-group border-0 px-0">
    	<label for="newsletterEmail" class="col-form-label border-0 d-none d-sm-inline-block ">Newsletter</label>
        <input name="email" type="email" class="form-control rounded-0" id="newsletterEmail" placeholder="Email" required="required">
        <input type="submit" class="invisible position-absolute">
    </div>
</form>