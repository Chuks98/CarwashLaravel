<!-- Price Start -->
<div class="price">
    <div class="container">
        <div class="section-header text-center">
            <h2>Washing Plans</h2>
            
            @if(session()->has('user') && session('user.role') === 'user')
                <p>Choose Your Plan</p>
            @endif
        </div>
        <div class="row" style="margin-top: 20px;">

            <div class="col-md-4">
                <div class="price-item">
                    <div class="price-header">
                        <h3>Basic Cleaning</h3>
                        <h3><span>₦</span>1,000</h3>
                    </div>
                    <div class="price-body">
                        <ul>
                            <li><i class="ti ti-check"></i>Seats Washing</li>
                            <li><i class="ti ti-check"></i>Vacuum Cleaning</li>
                            <li><i class="ti ti-check"></i>Exterior Cleaning</li>
                            <li><i class="ti ti-x"></i>Interior Wet Cleaning</li>
                            <li><i class="ti ti-x"></i>Window Wiping</li>
                        </ul>
                    </div>

                    @if(session()->has('user') && session('user.role') === 'user')
                        <div class="price-footer">
                            <form class="direct-subscribe-form" data-plan="basic">
                                <input class="planSelect" type="hidden" name="plan" value="basic">
                                <button type="submit" class="btn btn-custom">Subscribe Now</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="price-item featured-item">
                    <div class="price-header">
                        <h3>Premium Cleaning</h3>
                        <h3><span>₦</span>2,000</h3>
                    </div>
                    <div class="price-body">
                        <ul>
                            <li><i class="ti ti-check"></i>Seats Washing</li>
                            <li><i class="ti ti-check"></i>Vacuum Cleaning</li>
                            <li><i class="ti ti-check"></i>Exterior Cleaning</li>
                            <li><i class="ti ti-check"></i>Interior Wet Cleaning</li>
                            <li><i class="ti ti-x"></i>Window Wiping</li>
                        </ul>
                    </div>

                    @if(session()->has('user') && session('user.role') === 'user')
                        <div class="price-footer">
                            <form class="direct-subscribe-form" data-plan="premium">
                                <input class="planSelect" type="hidden" name="plan" value="premium">
                                <button type="submit" class="btn btn-custom">Subscribe Now</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="price-item">
                    <div class="price-header">
                        <h3>Complex Cleaning</h3>
                        <h3><span>₦</span>3,000</h3>
                    </div>
                    <div class="price-body">
                        <ul>
                            <li><i class="ti ti-check"></i>Seats Washing</li>
                            <li><i class="ti ti-check"></i>Vacuum Cleaning</li>
                            <li><i class="ti ti-check"></i>Exterior Cleaning</li>
                            <li><i class="ti ti-check"></i>Interior Wet Cleaning</li>
                            <li><i class="ti ti-check"></i>Window Wiping</li>
                        </ul>
                    </div>

                    @if(session()->has('user') && session('user.role') === 'user')
                        <div class="price-footer">
                            <form class="direct-subscribe-form" data-plan="complex">
                                <input class="planSelect" type="hidden" name="plan" value="complex">
                                <button type="submit" class="btn btn-custom">Subscribe Now</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Price End -->
