- [x] Base
    - [x] Conception Base (Fifaliana 3903 - Tojo 3910)  
    - [x] Script initialisation base (Tojo 3910)

- [x] Init codeigniter (Fifaliana 3903)
- [] Coté opérateur
    - 

- [] Coté client (Fifaliana 3903)
    - [] Login
        - [] Metier
            - [] AuthModel
                - [] verifierExistenceNum($num)
                    - [] check prefix regex (+261XX ou 0XX) 
            - [] AuthController
                - [] doLogin()
                    - [] Creation session
                    - [] verification
                    - [] redirection dashboard 
                        - [] message d'erreur
        - [] Affichage
            - [] auth/login.php
    - [] Dashboard Client
        - [] client/dashboard
            - [] Metier
                - [] voirSolde($id_compte)
                - [] getHistorique($id_compte)
                    - [] Limiter 10 dernières transactions
            - [] Base
            - [] Affichage
                - [] Carte qui affiche le solde
                - [] historique des transactions
        - [] client/operation
            - [] Metier
                - [] ajouterMouvement($id_client, $montant, $date, $type_operation)
                - [] transfert($id_client, $montant, $date,$tel_beneficiaire)
                    - [] validation numéro beneficiaire
                    - [] transaction
                    - [] creation de deux mouvements
            - [] Base
            - [] Affichage
                - [] champs: 
                    - [] montant
                    - [] type d'opération
                    - [] numéro beneficiaire
                        - [] ajout champ si transfert (JS)
            
        - [] client/historique
            - [] Metier
                - [] voirHistorique($id_client , $filtre[])
            - [] Base
            - [] Affichage
                - [] champs de filtres
                    - [] date (intervalle)
                    - [] montant (intervalle)
                    - [] type de transaction
                - [] liste historique 
                    - [] champs : date, montant, type, frais, béneficiaire (raha misy)  



        