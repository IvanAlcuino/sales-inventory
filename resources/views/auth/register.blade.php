@extends('layouts.app')

@section('content')
 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="full_name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-3">
                                <input id="full_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">    
                                <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required>

                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row" style="display:none">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" >

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }} / Mobile No.</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mobile_no" class="col-md-4 col-form-label text-md-right">Contact No. / Phone No.</label>

                            <div class="col-md-6">
                                <input id="mobile_no" type="text" class="form-control " name="mobile_no" value="{{ old('mobile_no') }}" required>

                                @if ($errors->has('mobile_no'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mobile_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="inputAddress">Address</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="inputAddress" placeholder="" name="address">
                            </div>
                        </div>

                        <div class="form-group row"> 
                            <label class="col-md-4 col-form-label text-md-right" for="inputCity">City</label>
                            <div class="col-md-6">    
                                <input type="text" class="form-control" id="inputCity" name="city">
                            </div>
                        </div>
                        <div class="form-group row">                            
                            <label class="col-md-4 col-form-label text-md-right" for="inputState">Province</label>
                            <div class="col-md-6">    
                                <select id="inputState" class="form-control" name="province">
                                <option selected>Choose...</option>
                                <option>Cebu</option>
                                <option>Bohol</option>
                                <option>Layte</option>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">                            
                            <label class="col-md-4 col-form-label text-md-right" for="inputState">Register Under:</label>
                            <div class="col-md-6 autocomplete">    
                                <input type="text" placeholder="Search Member"   class="form-control" 
                                    v-model="find_member"  
                                    @input="autoComplete"
                                    @keydown.down="onArrowDown"
                                    @keydown.up="onArrowUp"
                                    @keydown.enter="onEnter"
                                >
                                <div class="panel-footer"   v-show="isOpen">
                                    <ul id="autocomplete-found_members" class="list-group autocomplete-found_members"  >
                                        <li
                                            class="list-group-item loading"
                                            v-if="isLoading">
                                            Loading found_members...
                                        </li>
                                      
                                        <li   
                                            v-else
                                            v-for="(result, i) in found_members"
                                            :key="i"
                                            @click="setResult(result)"
                                            class="list-group-item autocomplete-result"
                                            :class="{ 'is-active': i === arrowCounter }"
                                        >
                                        @{{ result.name }}  
                                        </li>
                                    </ul>
                                </div> 
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 

<script >
        
        new Vue({
            el: '#app',
            data(){
                return {
                find_member: '',
                found_members: [], 
                selected_user_id: '',
                arrowCounter: -1,
                isOpen: false,
                isLoading: false,
                }
            },
            methods: {
                autoComplete(){
                    this.found_members = [];
                    if(this.find_member.length > 2){
                        this.isLoading = true;
                        axios.get('/pipe/user/search',{params: {find_member: this.find_member}}).then(response => {
                            this.found_members = response.data;
                            this.isOpen = true;
                            this.isLoading = false;

                        });
                    }
                },
                setResult(result) {
                    
                    this.find_member = result.name;
                    this.selected_user_id = result.id; 
                    this.arrowCounter = -1;
                    this.isOpen = false; 
                    
                },
                onArrowDown(evt) {
                    //alert("onArrowDown.. ");
                    //alert("arrowCounter:"+this.arrowCounter+" this.found_members.length:"+this.found_members.length);
                    if (this.arrowCounter < this.found_members.length) { 
                        this.arrowCounter = this.arrowCounter + 1;
                    }
                },
                onArrowUp() {
                    if (this.arrowCounter > 0) {
                    this.arrowCounter = this.arrowCounter -1;
                    }
                },
                onEnter() {
                    this.find_member = this.found_members[this.arrowCounter].name;
                    this.isOpen = false;
                    this.arrowCounter = -1;
                },
                handleClickOutside(evt) {
                    if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                    }
                }
                
            }
        })

        
        
    </script>     
    
    
          

@endsection
