@php
    $currentRoute = request()->route()->getName();
    $user = auth()->user();
@endphp
<aside class="account-sidebar">
    <div class="user-profile">
        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="username">{{ $user->name }}</div>
    </div>
    <nav class="account-nav">
        <ul>
            <li class="{{ $currentRoute === 'account.purchased-books' ? 'active' : '' }}">
                <a href="{{ route('account.purchased-books') }}"><span class="icon">📖</span> Sách đã mua</a>
            </li>
            <li class="{{ $currentRoute === 'account' ? 'active' : '' }}">
                <a href="{{ route('account') }}"><span class="icon">👤</span> Thông tin khách hàng</a>
            </li>
            <li class="{{ $currentRoute === 'account.change-password' ? 'active' : '' }}">
                <a href="{{ route('account.change-password') }}"><span class="icon">🔒</span> Đổi mật khẩu</a>
            </li>
            <li class="{{ $currentRoute === 'orders.index' ? 'active' : '' }}">
                <a href="{{ route('orders.index') }}"><span class="icon">🛒</span> Lịch sử mua hàng</a>
            </li>
            <li><a href="#"><span class="icon">💳</span> Lịch sử nạp tiền</a></li>
            <li><a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon">➡️</span> Đăng xuất</a></li>
        </ul>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</aside>

