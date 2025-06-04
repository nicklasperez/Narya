<template>
  <ion-page>
    <ion-tabs>
      <ion-router-outlet></ion-router-outlet>

      <ion-tab-bar slot="bottom">
        <ion-tab-button tab="home" href="/tabs/home" class="tab-btn-home">
          <ion-icon :icon="home" />
          <ion-label>Home</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="explora" href="/tabs/explora" class="tab-btn-explora">
          <ion-icon :icon="search" />
          <ion-label>Explora</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="nueva" href="/tabs/nueva" class="tab-btn-nueva">
          <ion-icon :icon="addCircle" />
          <ion-label>Nueva</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="graficos" href="/tabs/graficos" class="tab-btn-graficos">
          <ion-icon :icon="barChart" />
          <ion-label>Gráficos</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="ajustes" href="/tabs/ajustes" class="tab-btn-ajustes">
          <ion-icon :icon="settings" />
          <ion-label>Ajustes</ion-label>
        </ion-tab-button>

      </ion-tab-bar>
    </ion-tabs>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonTabs, IonTabButton, IonIcon, IonLabel, IonTabBar, IonRouterOutlet } from '@ionic/vue';
import { home, search, addCircle, barChart, settings } from 'ionicons/icons';
</script>

<style>

</style>
