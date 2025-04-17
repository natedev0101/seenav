<x-app-layout>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-white leading-tight">
            {{ __('Gyakran Ismételt Kérdések') }}
        </h2>
    </x-slot>
    <div class="container-fluid">
    
        <div class="col-11 col-sm-9 bg-menu div-info align-center">
            <div class="div-nav">
                <button id="gyik-bt">GYIK</button>
                <button id="gyik-leader-bt">GYIK - Leader</button>
            </div>

            <div id="gyik-div">
                <h1 class="text-2xl text-default bold-700">GYIK</h1>

                <hr>

                <h2 class="text-xl text-default bold-600 margin-top">1. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>

                <h2 class="text-xl text-default bold-600 margin-top">2. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>

                <h2 class="text-xl text-default bold-600 margin-top">3. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>

                <h2 class="text-xl text-default bold-600 margin-top">4. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>
            </div>

            <div id="gyik-leader-div" style="display: none;">
                <h1 class="text-2xl text-default bold-700">GYIK - Leader</h1>

                <hr>

                <h2 class="text-xl text-default bold-600 margin-top">1. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>

                <h2 class="text-xl text-default bold-600 margin-top">2. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>

                <h2 class="text-xl text-default bold-600 margin-top">3. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>

                <h2 class="text-xl text-default bold-600 margin-top">4. Kérdés</h2>
                <span class="text-default text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, voluptate, labore eum quod                         odit, nulla doloribus quidem hic et molestias error consequuntur placeat eius eligendi illum                         vitae excepturi itaque accusantium!
                </span>
            </div>
        </div>
    </div>
<style>
    .align-center {
    margin: auto;
}

.bg-menu {
    background-color: #1E2736;
}

.flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
}

.margin-top {
    margin-top: .7rem;
}

/* Text */

.text-2xl {
    font-size: 1.5rem;
    line-height: 2rem;
}

.text-xl {
    font-size: 1.25rem;
    line-height: 1.75rem;
}

.text-default {
    color: white;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: 700;
}

.text-sm {
    color: #bebebe;
    font-size: .8rem;
}

.bold-700 {
    font-weight: 700;
}

.bold-600 {
    font-weight: 600;
}

/* Menu Button */

.menu-bt {
    color: white;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    background-color: rgb(0, 0, 97);
    border-radius: 5px;
    border: 2px solid black;
    padding: 0 1%;
    margin: 0.5%;
}

.menu-bt:hover {
    background-color: #808080;
}

/* Div Info */

.div-info {
    margin-top: 1.5%;
    padding: 1.5rem;
    padding-top: 0.5rem;
    border-radius: .5rem;
}

.div-info hr {
    color: rgb(145, 145, 145);
}

.div-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #0c111b;
    border-radius: .5rem;
    padding: 0.5%;
    margin-bottom: .7rem;
}

.div-nav button {
    color: white;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: 600;
    background-color: #18243a;
    border-radius: .4rem;
    border: none;
    padding: 0.5% 1%;
    margin: .3rem .3rem;
}

.div-nav button:hover {
    background-color: #1f2e47;
}
</style>
<script>
    const nav_div = document.querySelector('.div-nav');

const gyik_div = document.getElementById("gyik-div");
const gyik_leader = document.getElementById("gyik-leader-div");

nav_div.addEventListener('click', function(event) {
    const target = event.target.closest('button');
    
    if (!target) {return}

    if (target.id == "gyik-bt") {
        gyik_div.style.display = "block";
        gyik_leader.style.display = "none";
    } else if (target.id == "gyik-leader-bt") {
        gyik_div.style.display = "none";
        gyik_leader.style.display = "block";
    }
});
</script>
</x-app-layout>