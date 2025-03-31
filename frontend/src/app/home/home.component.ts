import { Token } from '@angular/compiler';
import { Component, OnInit } from '@angular/core';
import { AuthStateService } from '../shared/auth-state.service';
import { Router } from '@angular/router';
import { TokenService } from '../shared/token.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {


    isSignedIn!: boolean;
    constructor(
      private auth: AuthStateService,
      public router: Router,
        public token: TokenService
        ) { }
  ngOnInit(){
    this.auth.userAuthState.subscribe((val) => 
      {
      this.isSignedIn = val;
    }
  );
    }
  }
